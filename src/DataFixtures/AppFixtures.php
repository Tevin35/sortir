<?php

namespace App\DataFixtures;

use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $encoder;
    private $faker;
    private $manager;

    public function __construct(UserPasswordHasherInterface $encoder){
        $this->encoder = $encoder;
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {


        $participant = new Participant();

        $participant
            ->setPseudo($faker->userName())
            ->setLastName($faker->lastName())
            ->setLastName($faker->lastName())
            ->setPhone($faker->phoneNumber())
            ->setEmail('user@test.com')
            ->





        $manager->flush();
    }
}
