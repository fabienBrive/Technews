<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 19/11/2018
 * Time: 14:23
 */

namespace App\Article\Mediator;


use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineSource
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getArticles()
    {
        $articles = $this->em->getRepository(Article::class)
            ->findAll();

        return $articles;

    }
}