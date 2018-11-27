<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 20/11/2018
 * Time: 10:12
 */

namespace App\Article\Mediator;


use App\Entity\Article;

interface ArticleRepositoryInterface
{
    /**
     * Permet de retourner un article grace à son identifiant unique
     * @param $id
     * @return mixed
     */
    public function find($id): ?Article;

    /**
     * Permet de récupérer la liste de tous les articles
     * @return iterable|null
     */
    public function findAll(): ?iterable;

    /**
     * Retourne les derniers articles
     * @return iterable|null
     */
    public function findLatestArticles(): ?iterable;

    /**
     * Retourne le nombre d'article de chaque source. Pour stats
     * @return int
     */
    public function count(): int;
}