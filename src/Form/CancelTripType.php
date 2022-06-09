<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Place;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CancelTripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('cancelMotif', TextareaType::class,[
                'mapped'=>true,
                'label' => "Motif d'annulation"

            ])

            ->add('annulerSortie', SubmitType::class, ['label'=>'Annuler la sortie'])
            ->add('retour', ButtonType::class, ['label'=>'Page précédente'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            //null => aucune entité n'est associée à ce formulaire
            'data_class' => null,
        ]);
    }
}
