<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 15/11/2018
 * Time: 10:27
 */

namespace App\Controller\TechNews;


use App\Newsletter\NewsletterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NewsletterController extends Controller
{
    /**
     * Affichage d'une Modal Newsletter
     */
    public function newsletter()
    {
        # Récupération du formulaire
        $form = $this->createForm(NewsletterType::class);

        # Transmission à la vue
        return $this->render('newsletter/_modal.html.twig', [
            'form' => $form->createView()
        ]);
    }
}