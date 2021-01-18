<?php

namespace App\Form;

use App\Entity\Episode;
use DateInterval;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'number',
                IntegerType::class,
                [
                    "label" => "Numéro de l'épisode"
                ]
                )
            ->add(
                'title',
                TextType::class,
                [
                    "label" => "Titre"
                ]
                )
            ->add(
                'duration',
                DateIntervalType::class,

                [
                    "label" => "Durée",
                    'widget' => 'integer',
                    'with_years' => false,
                    'with_months' => false,
                    'with_days' => false,
                    'with_hours' => true,
                    'with_minutes' => true
                ]

                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }
}
