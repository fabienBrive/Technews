<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 07/11/2018
 * Time: 15:43
 */

namespace App\Article\Provider;


use App\Controller\HelperTrait;
use App\Entity\Article;
use Symfony\Component\HttpKernel\KernelInterface;

class YamlProvider
{
    use HelperTrait;

    private $kernel;

    /**
     * YamlProvider constructor.
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }


    /**
     * Retourne les articles .yaml sous forme de tableau
     */
    public function getArticles()
    {
        $articles = unserialize( file_get_contents(
                $this->kernel->getCacheDir() . '/yaml-article.php'
            )
        );
        return $articles;
    }
}