<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 09/11/2018
 * Time: 10:08
 */

namespace App\Service\Twig;


use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    /**
     * Entity Manager
     */
    private $em;
    public const NB_SUMMURY_CHAR = 170;

    /**
     * AppExtension constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        #récupération du EntityManger de doctrine
        $this->em = $manager;
    }

    public function getFunctions()
    {
        return [
          new \Twig_Function('getCategories', function(){
              return $this->em->getRepository(Categorie::class)
                  ->findCartegoriesHavingArticles();
          })
        ];
    }

    public function getFilters()
    {
        return [
          new \Twig_Filter('summary', function($text){

              # supression des balises html
             $string = strip_tags($text);

             #Si le string dépasse 170, je continue :
             if (strlen($string) > self::NB_SUMMURY_CHAR) {

                 # je coupe le string à 170 caractères :
                 $stringCut = substr($string, 0 , self::NB_SUMMURY_CHAR);

                 # je coupe ma chaine au dernier espace trouvé :
                 $string = substr($stringCut, 0, strrpos($stringCut, ' ')) . '...';
             }
             return $string;
          }, ['is_safe' => ['html']])
        ];
    }


}