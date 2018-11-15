<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 12/11/2018
 * Time: 11:27
 */

namespace App\Controller;


use Behat\Transliterator\Transliterator;

trait HelperTrait
{
    /**
     * Permet de générer un Slug à partir d'un String
     * @param $text
     * @return String Slug
     * @see https://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string
     */
    public function slugify(string $text): string
    {
        return Transliterator::transliterate($text);
    }
}