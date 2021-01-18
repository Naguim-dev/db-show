<?php

namespace App\Form;

use App\Entity\Season;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'number',
                IntegerType::class,
                [
                    "label" => "Numéro de la saison",
                ]
                )
            ->add(
                'year',
                IntegerType::class,
                [
                    "label" => "Année"
                ]
                )
            ->add(
                'episodes',
                CollectionType::class,
                [
                    "label" => false,
                    "entry_type" => EpisodeType::class,
                    "entry_options" => ['label' => false],
                    "allow_add" => true,
                    "allow_delete" => true,
                    "by_reference" => false,
                    'prototype'    => true,
                ]
            )

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
