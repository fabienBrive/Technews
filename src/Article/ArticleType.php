<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 12/11/2018
 * Time: 14:04
 */

namespace App\Article;


use App\Article\EventListener\ArticleTypeSlugFieldSubscriber;
use App\Entity\Article;
use App\Entity\Categorie;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            # champ TitreArticle
            ->add('titre', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Titre de l\'article'
                ]
            ])
            # champ Categorie
            ->add('categorie', EntityType::class, [
                'class'         => Categorie::class,
                'choice_label'  => 'nom',
                'expanded'      => false,
                'label'         => false,
            ])

            # champ contenu
            ->add('contenu', CKEditorType::class, [
                'required'  => true,
                'label'     => false,
                'config'    => [
                    'toolbar' => 'standard'
                ]
            ])

            # champ featuredImage
            ->add('featuredImage', FileType::class, [
                'required' => true,
                'label'    => false,
                'attr'     => [
                    'class'  => 'dropify',
                    'data-default-file' => $options['image_url']
                ]
            ])

            # champ Special
            ->add('special', CheckboxType::class, [
                'required'  => false,
                'label'     => 'Article special',
                'attr'      => [
                    'data-toggle'   => 'toggle',
                    'data-on'       => 'Oui',
                    'data-off'      => 'Non'
                ]
            ])

            # champ Spotlight
            ->add('spotlight', CheckboxType::class, [
                'required'  => false,
                'label'     => 'Article Spotlight',
                'attr'      => [
                    'data-toggle'   => 'toggle',
                    'data-on'       => 'Oui',
                    'data-off'      => 'Non'
                ]
            ])

            # champ optionnel pour le slug
            ->addEventSubscriber(new ArticleTypeSlugFieldSubscriber())

            # champ Submit
            ->add('submit', SubmitType::class, [
                'label' => 'Publier'
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArticleRequest::class,
            'image_url' => null
        ]);
    }

}