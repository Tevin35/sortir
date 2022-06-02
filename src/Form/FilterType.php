<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Trip;
use App\Form\Model\SearchData;
use App\Repository\CampusRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder

            // Attention le child correspond toujours à l'attribut de classe que Symfony va chercher à mapper avec
            // la classe en question mais il le fait en automatique

            ->add('campus', EntityType::class, [
                //Nom de l'attribut que l'on veut utiliser pour l'affichage
                'choice_label' => 'name',
                'class' => Campus::class,

                'required' => false,
                'placeholder' => 'Choisir un campus',
                'query_builder' => function (CampusRepository $campusRepository) {
                    return $campusRepository->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                },
                'constraints' => new NotBlank(['message' => 'choisir un campus']),

            ])
            ->add('search', TextType::class, [
                'label' => 'le nom de la sortie contient',
                'required' => false,
                'attr' => ['placeholder' => 'Search']
            ])
            ->add('startingDate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => 'entre',
                'required' => false

            ])
            ->add('endingDate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => 'et',
                'required' => false

            ])

            ->add('ownerTrip', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
            ])

            ->add('registerTrip', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false,
            ])

            ->add('unsuscribeTrip', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
            ])

            ->add('pastTrip', CheckboxType::class, [
                'label' => 'Sorties passés',
                'required' => false,
            ])

        ;


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class
        ]);
    }
}
