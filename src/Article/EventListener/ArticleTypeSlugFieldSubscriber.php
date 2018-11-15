<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 14/11/2018
 * Time: 16:21
 */

namespace App\Article\EventListener;



use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class ArticleTypeSlugFieldSubscriber implements EventSubscriberInterface
{

    /**
     *
     */
    public static function getSubscribedEvents()
    {
        return[
            FormEvents::PRE_SET_DATA => 'preSetData'
        ];
    }

    public function preSetData(FormEvent $event)
    {
        $article = $event->getData();
        $form = $event->getForm();

        # Je vérifie la présence du slug
        if (null !== $article->getSlug()) {
            # dans ce cas je rajoute le champ Slug
            $form->add('slug', TextType::class, [
               'label' => 'Alias',
               'attr' => [
                   'placeholder' => "Alias de l'article"
               ]
            ]);
        }
    }
}