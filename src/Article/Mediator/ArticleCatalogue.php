<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 20/11/2018
 * Time: 11:30
 */

namespace App\Article\Mediator;


use App\Article\Mediator\ArticleAbstractSource;
use App\Entity\Article;
use Tightenco\Collect\Support\Collection;

class ArticleCatalogue implements ArticleCatalogueInterface
{
    /**
     * @var array
     */
    protected $sources = [];

    /**
     * @param \App\Article\Mediator\ArticleAbstractSource $source
     */
    public function addSource(ArticleAbstractSource $source): void
    {
        if (!in_array($source, $this->sources)) {
            $this->sources[] = $source;
        }
    }

    public function setSources(iterable $sources): void
    {
        $this->sources = $sources;
    }

    public function getSources(): iterable
    {
        return $this->sources;
    }

    /**
     * Permet de retourner un article grace à son identifiant unique
     * @param $id
     * @return mixed
     */
    public function find($id): ?Article
    {
        $articles = new Collection();

        /** @var ArticleAbstractSource $source */
        foreach ($this->sources as $source) {

            # J'appel la méthhode find de chaque source
            $article = $source->find($id);

            if (null !== $article) {
                $articles[] = $article;
            }
        }

        # Je retourne l'article de la dernière source
        return $article->pop();

    }

    /**
     * Permet de récupérer la liste de tous les articles
     * @return iterable|null
     */
    public function findAll(): ?iterable
    {
        return $this->sourcesIterator('findAll')
            ->sortBy('dateCreation');
    }

    /**
     * Retourne les derniers articles
     * @return iterable|null
     */
    public function findLatestArticles(): ?iterable
    {
        return $this->sourcesIterator('findLatestArticles')
            ->sortByDesc(function($col) {
                return $col->getDateCreation();
            })
            ->slice(-5);
    }

    /**
     * Retourne le nombre d'article de chaque source. Pour stats
     * @return int
     */
    public function count(): int
    {
        return count($this->sources);
    }

    /**
     * Parcours les sources
     * @param string $callback
     * @return Collection
     */
    private function sourcesIterator(string $callback): Collection
    {
        $articles = new Collection();

        /** @var ArticleAbstractSource $source */
        /** @var Article $article */
        foreach ($this->sources as $source) {
            foreach ($source->$callback() as $article) {
                    $articles[] = $article;
                }
        }
        return $articles;
    }


}