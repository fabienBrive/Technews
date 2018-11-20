<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 13/11/2018
 * Time: 14:14
 */

namespace App\Controller\TechNews\Security;


use App\Form\ConnexionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{

    /**
     * Connexion d'un membre
     * @Route({
     *     "fr": "/connexion",
     *     "en": "/login"
     * }, name="security_connexion", name="security_connexion")
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function connection(Request $request, AuthenticationUtils $authenticationUtils)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('index');
        }

        # Récupération du Formulaire de Connexion
        $form = $this->createForm(ConnexionType::class, [
            'email' => $authenticationUtils->getLastUsername()
        ]);

        # Récupération du message d'erreur
        $error = $authenticationUtils->getLastAuthenticationError();

        # Transmission à la vue
        return $this->render('security/connexion.html.twig', [
           'form' => $form->createView(),
           'error' => $error
        ]);
    }

    /**
     * Deconnexion d'un memebre
     * @Route({
     *     "fr": "/deconnexion",
     *     "en": "/logout"
     * }, name= "security_deconnexion")
     */
    public function deconnection()
    {
        /**
         * Vous pourriez définir aussi ici, votre logique, mot de passe oublié...
         * Réinitialisation du mdp et Email de validation...
         */
    }
}