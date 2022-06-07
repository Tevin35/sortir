<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Place;
use App\Entity\Trip;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;

class CreateTripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', \Symfony\Component\Form\Extension\Core\Type\TextType::class,[
                'label' => "Nom de la sortie*",
                'attr' => [
                    'autofocus required placeholder' => "Nom de la sortie*"
                ]
            ])
            ->add('dateStartHour', \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class,[
                'label' => "Date et heure de la sortie*",
                'widget' => 'single_text',
                'attr' => [
                    'required placeholder' => "Date et heure de la sortie",
                ]
            ])
            ->add('dateLimitRegistration',DateType::class,[
                'label' => "Date limite d'inscription*",
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'required placeholder'=>"Date limite d'inscription*"
                ]
            ])
            ->add('nbMaxRegistration', NumberType::class,[
                'label' => "Nombre de places"
            ])

            ->add('duration', NumberType::class,[
                'label' => "Durée"
            ])


            ->add('tripDescription', TextareaType::class,[
                'label' => "Description et infos"
            ])

            ->add('campus', EntityType::class, [

                'class' => Campus::class,
                'choice_label' => 'name'
            ])

            ->add('ville', EntityType::class, [
                'mapped' => false,
                'class' => City::class,
                'choice_label' => 'name'
            ])

            ->add('place', EntityType::class,[
                'class' => Place::class,
                'choice_label' => 'name'
            ])

            ->add('enregistrer', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('publier', SubmitType::class, ['label' => 'Publier'])
            ->add('supprimer', SubmitType::class, ['label' => 'Supprimer'])
            ->add('annuler', ResetType::class, ['label' => 'Annuler']);

            /*
            $builder->addEventListener(


                FormEvents::PRE_SET_DATA,
                function (FormEvent $event){
                    $form = $event->getForm();
                    dump($form);

                    $data = $event->getData();
                    dump($data);

                    $city = $data->getPlace()->getCity();
                    dump($city);
                    $places = null === $city ? [] : $city->getPlace();
                    dump($places);

                    $form->add('place', EntityType::class, [
                        'class' => Place::class,
                        'placeholder'=>'',
                        'choices'=>$places,
                    ]);
                }
            );
            */

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
