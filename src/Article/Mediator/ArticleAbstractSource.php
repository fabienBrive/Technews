<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 20/11/2018
 * Time: 10:06
 */

namespace App\Article\Mediator;


use App\Article\Mediator\ArticleRepositoryInterface;
use App\Entity\Article;

abstract class ArticleAbstractSource implements ArticleRepositoryInterface
{
    /**
     * Permet de convertir un tableau en Article
     * @param iterable $article
     * @return Article|null
     */
    abstract protected function createArticleFromArray(iterable $article): ?Article;

}