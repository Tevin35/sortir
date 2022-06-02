<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

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
            ->add('campus', EntityType::class, [
                //Nom de l'attribut que l'on veut utiliser pour l'affichage
                'choice_label' => 'name',
                'class' => Campus::class,
                'required' => false,
                'placeholder' => 'Choisir un campus',
                'query_builder' => function (CampusRepository $campusRepository) {
                    return $campusRepository->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                },
                'constraints' => new NotBlank(['message' => 'choisir un campus'])])
            ->add('email', TextareaType::class, [
                'label' => "Email : ",
            ])
            ->add('password', PasswordType::class, [
                'label' => "Mot de passe : ",
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 100,
                    ])
                ]
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
