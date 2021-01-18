<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Character;
use App\Entity\Person;
use App\Entity\TvShow;
use App\Repository\CategoryRepository;
use App\Repository\PersonRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class TvShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                "title", 
                TextType::class,
                [
                    "label" => "Titre de la série",
                    "attr" =>["placeholder" => "ex: The Mandalorian"]
                ])
            ->add(
                "synopsis",
                TextareaType::class,
                [
                    "label" => "Synopsis",
                    "required" => false,
                    "attr" => [
                        "rows" => 5
                    ]
                ])
            ->add(
                "releaseDate",
                DateType::class,
                [
                    "label" => "Date de première diffusion",
                    "required" => false,
                    "widget" => "single_text"
                ])
                
            ->add(
                "categories",
                EntityType::class, [
                    "class" => Category::class,
                    "choice_label" => "label",
                    "multiple" => true,
                    "expanded" => true,
                    "attr" => [
                        "class" => "compact-select-list"
                    ],
                    'query_builder' => function (CategoryRepository $er) {
                        return $er->createQueryBuilder('category')
                            ->orderBy('category.label', 'ASC');
                    },
                ]
                )
            ->add(
                "directedBy",
                EntityType::class, [
                    "class" => Person::class,
                    "choice_label" => function (Person $person) {
                        return $person->getFullName();
                    },
                    'query_builder' => function (PersonRepository $er) {
                        return $er->createQueryBuilder('person')
                                  ->orderBy('person.firstName', 'ASC');
                    },
                    "required" => false
                ]
                )
            ->add(
                "poster",
                FileType::class,
                [
                    "label" => "Poster de la série",
                    "required" => false,
                    "mapped" => false,
                    'constraints' => [
                        new Image([
                            'maxSize' => '1024k'
                        ])
                    ],
                ]
                );


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TvShow::class,
        ]);
    }
}
