<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 08/11/2018
 * Time: 11:57
 */

namespace App\Controller\TechNews;


use App\Article\ArticleFactory;
use App\Article\ArticleRequest;
use App\Article\ArticleRequestHandler;
use App\Article\ArticleRequestUpdateHandler;
use App\Article\ArticleType;
use App\Article\Mediator\ArticleMediator;
use App\Article\Mediator\DoctrineProvider;
use App\Article\Mediator\DoctrineSource;
use App\Article\Mediator\YamlMediator;
use App\Article\Mediator\YamlSource;
use App\Article\Provider\YamlProvider;
use App\Controller\HelperTrait;
use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Membre;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use function Sodium\crypto_box_publickey_from_secretkey;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;


class ArticleController extends Controller
{
    use HelperTrait;
    /**
     * Démonstration de l'jout d'un article avec Doctrine
     * @Route("test/article/add", name="article_test")
     */
    public function test()
    {
        # création d'une catégorie
        $categorie = new Categorie();
        $categorie->setNom('Economie');
        $categorie->setSlug('economie');

        # création d'un Membre (Auteur de l'article)
        $membre = new Membre();
        $membre->setPrenom('Fabien');
        $membre->setNom('Brive');
        $membre->setEmail('f.brive@tech.news');
        $membre->setPassword('Monchatfait des calins');
        $membre->setRoles(['Auteur']);

        # création d'un Articles
        $article = new Article();
        $article->setTitre('WebForce rachète un véoprojecteur Pour Nicolas');
        $article->setSlug('webforce-rachete-un-videoprojecteur-pour-nicolas');
        $article->setContenu(
            'Aprés une semaine de deisette, Webforce3 a enfin décidé de 
            racheter un vidoeproj pour aider nicolas dans sa tache de formation. On les félicite 
            vivement de cette décision et prenons acte de celle ci'
        );
        $article->setFeaturedImage('7.jpg');
        $article->setSpecial(0);
        $article->setSpotlight(1);

        # On associe une ctégorie et un auteur à l'article
        $article->setCategorie($categorie);
        $article->setMembre($membre);

        #on sauvegarde le tout avec Doctrine
        $em = $this->getDoctrine()->getManager();
        $em->persist($categorie);
        $em->persist($membre);
        $em->persist($article);
        $em->flush();

        return new Response(
            'Nouvel Article d\'ID : '
            . $article->getId()
            . ' dans la catégorie : '
            . $categorie->getNom()
            . ' de l\'auteur '
            . $membre->getPrenom()
        );
    }

    /**
     * Formulaire pour ajouter un article.
     * @Route({
     *     "fr": "/creer-un-article.html",
     *     "en": "/create-article.html"
     * }, name="article_new")
     * @Security("has_role('ROLE_AUTEUR')")
     * @param Request $request
     * @param ArticleRequestHandler $articleRequestHandler
     * @return Response
     */
    public function newArticle(Request $request, ArticleRequestHandler $articleRequestHandler)
    {
        # Création d'un nouvel Article
        $article = new ArticleRequest($this->getUser());

        # créer un Formulaire permettant l'ajout d'un article
        $form = $this->createForm(ArticleType::class, $article)
                     ->handleRequest($request);

        #verifiaction des donnes du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            # Récupération des données de notre article
            /**
             * Une fois le formuulaire soumit et validé,
             * on passe nos données directement au service qui se chargera du traitement de l'article
             */
            $article = $articleRequestHandler->handle($article);

            # Flash Message
            $this->addFlash('notice', 'Féléicitation votre article est en ligne!');

            # Redirection sur l'article qui veint d'être créé
            return $this->redirectToRoute('index_article', [
                # {categorie<\w+>}/{slug}_{id<\d+>}.html
                'categorie' => $this->slugify($article->getCategorie()->getnom()),
                'slug' => $article->getSlug(),
                'id' => $article->getId()
            ]);
        }
        # Affichage du formulaire dans la Vue
        return $this->render('article/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet à l'auteur, un Editeur ou un Admin d'éditer/modifier un article
     * @Route({
     *     "fr": "/editer-un-article/{id<\d+>}.html",
     *     "en": "/edit-article/{id<\d+>}.html"
     * }, name="article_edit")
     * @Security("article.isAuteur(user) or has_role('ROLE_EDITEUR')")
     * @param Article $article
     * @param Request $request
     * @param Packages $packages
     * @param ArticleRequestUpdateHandler $updateHandler
     * @return Response
     */
    public function editArticle(
        Article $article,
        Request $request,
        Packages $packages,
        ArticleRequestUpdateHandler $updateHandler)
    {
        # récupération de ArticleRequest depuis Article
        $ar = ArticleRequest::createFromArticle(
            $article,
            $this->getParameter('articles_assets_dir'),
            $this->getParameter('articles_dir'),
            $packages);

        # Crée le formulaire a partir de cette request
        $options = [
          'image_url' => $ar->getImageUrl()
        ];

        $form = $this->createForm(ArticleType::class, $ar, $options)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            #traitement et sauvegarde des données
            $article = $updateHandler->handle($ar, $article);

            # Flash Message
            $this->addFlash('notice', 'Modification effectuée !');

            return $this->redirectToRoute('article_edit',[
                'id' => $article->getId()
            ]);
        }

        # Affichage du formulaire dans la vue
        return $this->render('article/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}