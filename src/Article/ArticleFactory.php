<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 12/11/2018
 * Time: 15:34
 */

namespace App\Article;


use App\Article\ArticleRequest;
use App\Entity\Article;

class ArticleFactory
{
    /**
     * Création d'un objet Article à partier d'un article Request.
     * Pour insertion en BDD.
     * @param ArticleRequest $request
     * @return Article
     */
    public function createFromArticleRequest(ArticleRequest $request): Article
    {
        return new Article(
            $request->getId(),
            $request->getTitre(),
            $request->getSlug(),
            $request->getContenu(),
            $request->getFeaturedImage(),
            $request->getSpecial(),
            $request->getSpotlight(),
            $request->getCategorie(),
            $request->getMembre(),
            $request->getDateCreation()
        );
    }
}