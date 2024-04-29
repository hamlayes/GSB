<?php

namespace App\Form;

use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaisirLigneFraisForfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder
            ->add('nombreKm', IntegerType::class, [
                'label' => 'Nombre de kilomètre : ',
                'attr' => [
                    'min' => 0,
                ],

            ])
            ->add('nuitee', IntegerType::class, [
                'label' => 'Nombre de nuitée : ',
                'attr' => [
                    'min' => 0,
                ],
            ])
            ->add('repas', IntegerType::class, [
                'label' => 'Nombre de repas : ',
                'attr' => [
                    'min' => 0,
                ],
            ])
            ->add('etape', IntegerType::class, [
                'label' => 'Nombre d\'étape : ',
                'attr' => [
                    'min' => 0,
                ],
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Voir la fiche',  // Texte du bouton
                'attr' => [
                    'class' => 'btn btn-warning',
                    'style' => 'font-size: 16px;',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
