<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Place;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CancelTripType extends AbstractType
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

            ->add('campus', EntityType::class, [

                'class' => Campus::class,
                'choice_label' => 'name'
            ])

            ->add('place', EntityType::class,[
                'class' => Place::class,
                'choice_label' => 'name'
            ])

            ->add('motif', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
