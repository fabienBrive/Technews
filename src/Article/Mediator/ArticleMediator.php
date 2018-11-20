<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 19/11/2018
 * Time: 14:16
 */

namespace App\Article\Mediator;


use App\Article\Provider\YamlProvider;

class ArticleMediator implements MediatorInterface
{
    private $doctrineArticles, $yamlArticles;

    public function __construct(YamlProvider $yamlArticles, DoctrineSource $doctrineArticles)
    {
        $this->yamlArticles = $yamlArticles;
        $this->doctrineArticles = $doctrineArticles;

    }
    
    public function getAllArticles()
    {
        $articlesD = $this->doctrineArticles->getArticles();
        $articlesY = $this->yamlArticles->getArticles();

        return array_merge($articlesD, $articlesY);


    }
}