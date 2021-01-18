<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class UserPasswordUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'oldPassword',
                PasswordType::class,
                [
                    "label" => "Mot de passe actuel",
                    "mapped" => false,
                    "constraints" => [
                        new UserPassword()
                    ]
                ]
            )
            ->add(
                'newPassword', 
                RepeatedType::class, [
                    "mapped" => false,
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe ne sont pas identiques',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'first_options'  => ['label' => 'Nouveau mot de passe'],
                    'second_options' => ['label' => 'Répéter le nouveau mot de passe']
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
