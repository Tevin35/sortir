<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Place;
use App\Entity\Trip;
use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{


    // On instancie le Faker en langue française
    /**
     * @var Generator $faker
     */

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->hasher = $passwordHasher;
        $this->faker = Factory::create('fr_FR');
    }


    public function load(ObjectManager $manager): void
    {

        $this->loadCity($manager);
        $this->loadPlace($manager);




    }





    public function loadCity(ObjectManager $manager){

        for($i = 1; $i <= 10; $i++){

            $newCity = new City();

            $newCity
                ->setName($this->faker->city)
                ->setPostalCode($this->faker->countryCode);

            $manager->persist($newCity);
        }

        $manager->flush();


    }

    public function loadPlace(ObjectManager $manager){

        $tabPlace = ['cinema', 'bar', 'piscine', 'chez mémé', 'travail', 'ENI'];

        for($i = 1; $i <= 10; $i++){

            $newPlace = new Place();
            $tabCity = $this->$manager->getRepository(City::class)->FindAll();

            $newPlace
                ->setName($this->faker->randomElement($tabPlace))
                ->setCity($this->faker->randomElement($tabCity))
                ->setStreet($this->faker->streetName)
            ;

            $manager->persist($newPlace);
        }

        $manager->flush();

    }








}
