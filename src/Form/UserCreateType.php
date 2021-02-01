<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstName',
                TextType::class,
                [
                    'label' => 'Prénom'
                ]
            )
            ->add(
                'lastName',
                TextType::class,
                [
                    'label' => 'Nom'
                ]
            )
            ->add(
                'gender',
                ChoiceType::class,
                [
                    'label' => 'Genre',
                    'placeholder' => 'Choisissez :',
                    'choices' =>
                    [
                        'Femme' => 'Femme',
                        'Homme' => 'Homme'
                    ]
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    "label" => "Adresse email"
                ]
            )
            ->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe ne sont pas identiques',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'first_options'  => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Répéter le mot de passe']
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
