<?php

namespace App\Article\Mediator\Sources;


use App\Article\Mediator\ArticleAbstractSource;
use App\Article\Provider\YamlProvider;
use App\Controller\HelperTrait;
use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Membre;
use Tightenco\Collect\Support\Collection;

class YamlSource extends ArticleAbstractSource
{
    use HelperTrait;

    private $articles;

    public function __construct(YamlProvider $yamlProvider)
    {
        $this->articles = new Collection($yamlProvider->getArticles());
    }

    /**
     * Permet de convertir un tableau en Article
     * @param iterable $article
     * @return Article|null
     */
    protected function createArticleFromArray(iterable $article): ?Article
    {
        {
            $tmp = (object)$article;

            # Construire l'objet categorie
            // FIXME : Attention aux aid qui risuqent d'être différents
            $categorie = new Categorie();
            $categorie->setId($tmp->categorie['id']);
            $categorie->setNom($tmp->categorie['libelle']);
            $categorie->setSlug($this->slugify($tmp->categorie['libelle']));

            # Construire l'objet Auteur
            $auteur = new Membre();
            $auteur->setId($tmp->auteur['id']);
            $auteur->setNom($tmp->auteur['nom']);
            $auteur->setPrenom($tmp->auteur['prenom']);
            $auteur->setEmail($tmp->auteur['email']);

            # Construire l'objet Article
            $date = new \DateTime();
            return new Article(
                $tmp->id,
                $tmp->titre,
                $this->slugify($tmp->titre),
                $tmp->contenu,
                $tmp->featuredimage,
                $tmp->special,
                $tmp->spotlight,
                $categorie,
                $auteur,
                (new \DateTime())->setTimestamp($tmp->datecreation)
            );
        }
    }

    /**
     * Permet de retourner un article grace à son identifiant unique
     * @param $id
     * @return mixed
     */
    public function find($id): ?Article
    {
        $article = $this->articles->firstWhere('id', $id);
        return $article == null ? null : $this->createArticleFromArray($article);
    }

    /**
     * Permet de récupérer la liste de tous les articles
     * @return iterable|null
     */
    public function findAll(): ?iterable
    {
        $articles = new Collection();
        foreach ($this->articles as $article) {
            $articles[] = $this->createArticleFromArray($article);
        }
        return $articles;
    }

    /**
     * Retourne les derniers articles
     * @return iterable|null
     */
    public function findLatestArticles(): ?iterable
    {
        /** @var Collection $article */
        $article = $this->findAll();
        return $article->sortBy('datecreatoin')
            ->slice(-5);
    }


    /**
     * Retourne le nombre d'article de chaque source. Pour stats
     * @return int
     */
    public function count(): int
    {
        $this->articles->count();
    }
}