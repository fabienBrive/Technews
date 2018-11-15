<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 13/11/2018
 * Time: 10:01
 */

namespace App\Membre;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;

class MembreType extends AbstractType
{
    # Formulaire d'inscription du membre ...
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class,[
                'label' => 'Votre prénom :',
                'attr' => [
                    'placeholder' => 'Prénom...'
                ]
            ])
            ->add('nom', TextType::class,[
                'label' => 'Votre nom :',
                'attr' => [
                    'placeholder' => 'Nom...'
                ]
            ])
            ->add('email', EmailType::class,[
                'label' => 'Votre email :',
                'attr' => [
                    'placeholder' => 'Email...'
                ]
            ])
            ->add('password', PasswordType::class,[
                'label' => 'Votre mot de passe :',
                'attr' => [
                    'placeholder' => 'Mot de passe...'
                ]
            ])
            ->add('conditions', CheckboxType::class,[
                'label' => 'J\'accepte les CGU',
                'attr' => [
                    'data-toggle' => 'toggle',
                    'data-on' => 'Oui',
                    'data-off' => 'Non'
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Je m\'inscris !'
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MembreRequest::class
        ]);
    }

}