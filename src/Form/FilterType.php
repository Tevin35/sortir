<?php

namespace App\Form;

use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('dateStartHour')
            ->add('duration')
            ->add('dateLimitRegistration')
            ->add('nbMaxRegistration')
            ->add('tripDescription')
            ->add('state')
            ->add('place')
            ->add('campus')
            ->add('owner')
            ->add('registeredParticipants')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
