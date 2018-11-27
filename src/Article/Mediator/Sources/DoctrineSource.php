<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 20/11/2018
 * Time: 10:06
 */

namespace App\Article\Mediator\Sources;


use App\Article\Mediator\ArticleAbstractSource;
use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;

class DoctrineSource extends ArticleAbstractSource
{

    private $repository;
    private $entity = Article::class;

    public function __construct(ObjectManager $manager)
    {
        $this->repository = $manager->getRepository($this->entity);
    }

    /**
     * Permet de convertir un tableau en Article
     * @param iterable $article
     * @return Article|null
     */
    protected function createArticleFromArray(iterable $article): ?Article
    {
        return null;
    }

    /**
     * Permet de retourner un article grace à son identifiant unique
     * @param $id
     * @return mixed
     */
    public function find($id): ?Article
    {
        return $this->repository->find($id);
    }

    /**
     * Permet de récupérer la liste de tous les articles
     * @return iterable|null
     */
    public function findAll(): ?iterable
    {
        return $this->repository->findAll();
    }

    /**
     * Retourne les derniers articles
     * @return iterable|null
     */
    public function findLatestArticles(): ?iterable
    {
        return $this->repository->findLatestArticles();
    }

    /**
     * Retourne le nombre d'article de chaque source. Pour stats
     * @return int
     */
    public function count(): int
    {
        return $this->repository->countArticles();
    }
}