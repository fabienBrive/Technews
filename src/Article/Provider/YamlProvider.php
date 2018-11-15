<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 07/11/2018
 * Time: 15:43
 */

namespace App\Article\Provider;


use Symfony\Component\Yaml\Yaml;

class YamlProvider
{
    /**
     *
     */
    public function getArticles()
    {
        $articles = Yaml::parseFile(__DIR__ . '/Articles.yaml');
        return $articles['data'];
    }
}