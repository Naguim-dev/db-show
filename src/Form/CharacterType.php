<?php

namespace App\Form;

use App\Entity\Character;
use App\Entity\Person;
use App\Repository\PersonRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class CharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                "name",
                TextType::class,
                [
                    "label" => "Nom du personnage"
                ]
                )
            ->add(
                "actors",
                EntityType::class,

                [
                    "label" => "Liste des acteurs",
                    "class" => Person::class,
                    "choice_label" => function (Person $person) {
                        return $person->getFullName();   
                    },
                    "required" => false,
                    "multiple" => true,
                    "expanded" => true,
                    "attr" => [
                        "class" => "compact-select-list"
                    ],
                    'query_builder' => function (PersonRepository $er) {
                        return $er->createQueryBuilder('person')
                                  ->orderBy('person.firstName', 'ASC');
                    },
                ]
                )
            
            ->add(
                "picture",
                FileType::class,
                [
                    "label" => "Photo du personnage",
                    "required" => false,
                    "mapped" => false,
                    'constraints' => [
                        new Image([
                            'maxSize' => '1024k'
                        ])
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Character::class,
        ]);
    }
}
