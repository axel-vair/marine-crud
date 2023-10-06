<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Boat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-4 mt-2'
                ],
                'label' => 'Nom'
            ])
            ->add('identifier', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-4 mt-2'
                    ],
                'label' => 'Identifiant'
                ])
            ->add('length', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control mb-4 mt-2'
                    ],
                'label' => 'Longueur'
                ])

            ->add('tonnage', IntegerType::class,  [
                'attr' => [
                    'class' => 'form-control mb-4 mt-2'
                ],
                'label' => 'Poids'
            ])
            ->add('classe', EntityType::class, [
                'class' => Classe::class,
                'label' => 'Classes',
                'attr' => [
                    'class' => 'form-select mb-4 mt-2'
                ],
            ])



            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter un navire',
                'attr' => [
                    'class' => 'btn btn-outline-success'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Boat::class,
        ]);
    }
}
