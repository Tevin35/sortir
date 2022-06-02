<?php

namespace App\Form;

use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextareaType::class, [
                'label' => "Pseudo : ",
                ])
            ->add('firstName', TextareaType::class, [
            'label' => "Prénom : ",
                 ])
            ->add('lastName', TextareaType::class, [
                'label' => "Nom : ",
            ])
            ->add('phone', TextareaType::class, [
            'label' => "Téléphone : ",
            ])
            ->add('email', TextareaType::class, [
                'label' => "Email : ",
            ])
            ->add('password', TextareaType::class, [
                'label' => "Mot de passe : ",
            ]);
/*
MANQUE MDP, CONFIRMATION, CAMPUS ET PHOTO
*/

        /* TROUVER COMMENT METTRE ACTIVE PAR DEFAUT */



    /*        $builder
            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('pseudo')
            ->add('lastName')
            ->add('firstName')
            ->add('phone')
            ->add('active')
            ->add('campus')
            ->add('participantTrips')
        ;*/
    }

/*    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }*/
}
