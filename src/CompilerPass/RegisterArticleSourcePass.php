<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 20/11/2018
 * Time: 15:53
 */

namespace App\CompilerPass;


use App\Article\Mediator\ArticleCatalogue;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterArticleSourcePass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {

        # s'il n'y à pas de médiateur, on ne le charge pas...
        if (!$container->hasDefinition(ArticleCatalogue::class)) {
            return;
        }

        $articleCatalogueDefinition = $container->getDefinition(ArticleCatalogue::class);
        $taggedServices = $container->findTaggedServiceIds('app.article_source');

        foreach ($taggedServices as $source => $tags) {
            $articleCatalogueDefinition->addMethodCall('addSource', [
                new Reference($source)
            ]);
        }

    }


}