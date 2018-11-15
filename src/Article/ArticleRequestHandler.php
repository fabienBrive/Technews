<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 12/11/2018
 * Time: 15:04
 */

namespace App\Article;


use App\Controller\HelperTrait;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleRequestHandler
{
    use HelperTrait;

    private $em, $articleAssetsDir, $articleFactory;

    /**
     * ArticleRequestHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param ArticleFactory $articleFactory
     * @param string $articleAssetsDir
     */
    public function __construct(EntityManagerInterface $entityManager,
                                ArticleFactory $articleFactory,
                                string $articleAssetsDir )
    {
        $this->em = $entityManager;
        $this->articleFactory = $articleFactory;
        $this->articleAssetsDir = $articleAssetsDir;
    }


    public function handle(ArticleRequest $request): ?Article
    {
        # Traitement de l'upload de l'image
        /** @var UploadedFile $image */
        $image = $request->getFeaturedImage();

        if ($image !== null) {
            # Nom du fichier
            $fileName = $this->slugify($request->getTitre()) . '.' . $image->guessExtension();

            try {
                $image->move(
                    $this->articleAssetsDir,
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            # Mise a jour de l'image
            $request->setFeaturedImage($fileName);
        } else {
            return null;
        }
        # Mise en place du Slug
        $request->setSlug($this->slugify($request->getTitre()));

        #apel de la factory
        $article = $this->articleFactory->createFromArticleRequest($request);

        # Insertion en BDD
        $this->em->persist($article);
        $this->em->flush();

        return $article;
    }
}