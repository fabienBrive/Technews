<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 20/11/2018
 * Time: 12:00
 */

namespace App\Article\Mediator;


interface ArticleCatalogueInterface extends ArticleRepositoryInterface
{
    public function addSource(ArticleAbstractSource $source):void;

    public function setSources(iterable $sources):void;

    public function getSources(): iterable;

}