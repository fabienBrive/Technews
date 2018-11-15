<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 13/11/2018
 * Time: 10:52
 */

namespace App\Controller\TechNews;


use App\Membre\MembreRequest;
use App\Membre\MembreRequestHandler;
use App\Membre\MembreType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MembreController extends Controller
{
    /**
     * Inscription d'un utilisateur
     * @Route("/inscription", name="membre_inscription", methods={"GET","POST"})
     * @param Request $request
     * @param MembreRequestHandler $membreRequestHandler
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function inscription(Request $request, MembreRequestHandler $membreRequestHandler)
    {
        #création d'un nouvel utilisateur
        $membre = new MembreRequest();

        #création du formulaire
        $form = $this->createForm(MembreType::class, $membre)
            ->handleRequest($request);

        #vérification et traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            # Enregistrement de l'utilisateur
            $membre = $membreRequestHandler->handle($membre);

            # flash Message
            $this->addFlash('notice', 'Félicitation, vous pouvez vous connecter !');

            # Redirection vers :
            return $this->redirectToRoute('security_connexion');
        }

        # affichage du formulaire
        return $this->render('membre/inscritpion.html.twig', [
           'form' => $form->createView()
        ]);
    }
}