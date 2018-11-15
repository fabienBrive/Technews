<?php

namespace App\Article;


use App\Controller\HelperTrait;
use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleRequestUpdateHandler
{
    use HelperTrait;

    private $em, $articleAssetsDir;

    /**
     * ArticleRequestUpdateHandler constructor.
     * @param ObjectManager $manager
     * @param string $articleAssetsDir
     */
    public function __construct(ObjectManager $manager, string $articleAssetsDir)
    {
        $this->em = $manager;
        $this->articleAssetsDir = $articleAssetsDir;
    }

    public function handle(ArticleRequest $request, Article $article)
    {
        # Traitement de l'upload de l'image
        /** @var UploadedFile $image */
        $image = $request->getFeaturedImage();

        #vérifier si l'utilisateur a soumis une nouvelle image
        if ($image !== null) {
            # génération du nom du fichier
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
            $request->setFeaturedImage($article->getFeaturedImage());
        }
        # Mise a jour des données
        $article->update(
            $request->getTitre(),
            $this->slugify($request->getTitre()),
            $request->getContenu(),
            $request->getFeaturedImage(),
            $request->getSpecial(),
            $request->getSpotlight(),
            $request->getCategorie()
        );

        # Enregistrement en BDD
        $this->em->flush();

        # On retourne notre article
        return $article;
    }
}