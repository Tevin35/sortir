<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $encoder;
    private $faker;
    private ObjectManager $manager;


    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {

        $this->manager = $manager;

        $this->addCampus($manager);
        $this->addParticipant($manager);

    }

    private function addCampus(ObjectManager $manager)
    {
        $campus1 = new Campus();
        $campus1->setName('Niort');
        $manager->persist($campus1);

        $campus2 = new Campus();
        $campus2->setName('Rennes');
        $manager->persist($campus2);

        $campus3 = new Campus();
        $campus3->setName('Nantes');
        $manager->persist($campus3);

        $campus4 = new Campus();
        $campus4->setName('Quimper');
        $manager->persist($campus4);

        $manager->flush();
    }

    private function addParticipant(ObjectManager $manager)
    {
        $participant = new Participant();

        $campus = $this->manager->getRepository(Campus::class)->FindAll();
        $participant
            ->setPseudo($this->faker->userName())
            ->setLastName($this->faker->lastName())
            ->setFirstName($this->faker->firstName())
            ->setPhone($this->faker->phoneNumber())
            ->setEmail($this->faker->email)
            ->setRoles(['ROLE_USER'])
            ->setCampus($this->faker->randomElement($campus))
            ->setActive(true);
        $password = $this->encoder->hashPassword($participant, 'password');
        $participant->setPassword($password);
        $manager->persist($participant);
        $manager->flush();
    }











}
