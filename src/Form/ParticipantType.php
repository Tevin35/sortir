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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ParticipantType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder
            ->add('pseudo', TextareaType::class, [
                'label' => "Pseudo : ",
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre pseudo doit contenir au moins {{ limit }} caractères',
                        'max' => 50,
                    ])
                ]
            ])
            ->add('firstName', TextareaType::class, [
            'label' => "Prénom : ",
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre prénom doit contenir au moins {{ limit }} caractères',
                        'max' => 50,
                    ])
                ]
                 ])
            ->add('lastName', TextareaType::class, [
                'label' => "Nom : ",
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre nom de famille doit contenir au moins {{ limit }} caractères',
                        'max' => 50,
                    ])
                ]
            ])
            ->add('phone', TelType::class, [
            'label' => "Téléphone : ",
                'invalid_message' => "Veuillez entrer un numéro de téléphone valide."
            ])
            ->add('email', EmailType::class, [
                'label' => "Email : ",
                'invalid_message' => "Veuillez entrer une adresse mail valide."
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Les deux mots de passe doivent se correspondre.",
                'options' => ['attr' => ['class' => 'password_field']],
                'required' => false,
                'mapped' => false,
                'empty_data' => '',
                'first_options' => ['label' => 'Mot de passe : '],
                'second_options' => ['label' => 'Confirmation :' ],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 100,
                    ])
                ]
            ])
            ->add('campus', EntityType::class, [
                //Nom de l'attribut que l'on veut utiliser pour l'affichage
                'choice_label' => 'name',
                'class' => Campus::class,
                'required' => true,
                'placeholder' => 'Choisir un campus',
                'query_builder' => function (CampusRepository $campusRepository) {
                    return $campusRepository->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                },
                'constraints' => new NotBlank(['message' => 'choisir un campus'])])
            ->add('brochure', FileType::class, [
                'label' => 'Photo de profil (fichier attendu : JPG, PNG, GIF)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader un fichier jpg, png ou gif valide',
            ])]])
;

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

  public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }


}
