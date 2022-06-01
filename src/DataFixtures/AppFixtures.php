<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Participant;
use App\Entity\Place;
use App\Entity\State;
use App\Entity\Trip;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Length;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $encoder;
    private Generator $faker;
    private ObjectManager $manager;

    const DEFAULT_CAMPUS = ['Niort', 'Rennes', 'Quimper', 'Nantes'];
    const DEFAULT_STATE = [
        'CREA' => 'en création',
        'OPEN' => 'ouverte',
        'FENC' => 'clôturée',
        'PROG' => 'en cours',
        'CLOS' => 'terminée',
        'HIST' => 'historisée',
        'CANC' => 'annulée'
    ];


    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {

        $this->manager = $manager;

        $this->addCampus();
        $this->addParticipant();
        $this->addState();
        $this->addTrip();

    }

    private function addCampus()
    {

        foreach (self::DEFAULT_CAMPUS as $campusName) {
            $campus = new Campus();
            $campus->setName($campusName);
            $this->manager->persist($campus);
        }

        $this->manager->flush();
    }


    private function addParticipant()
    {
        for ($i = 1; $i <= 10; $i++) {
            $participant = new Participant();

            $campus = $this->manager->getRepository(Campus::class)->findAll();

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
            $this->manager->persist($participant);

        }
        $this->manager->flush();
    }

    private function addState()
    {

        foreach (self::DEFAULT_STATE as $code =>$worded) {
            $state = new State();
            $state->setWorded($worded);
            $state->setStateCode($code);
            $this->manager->persist($state);
        }

        $this->manager->flush();

    }

    private function addTrip()
    {
        $activities = ['babyfoot', 'patinoire', 'cinéma', 'fête forraine', 'randonée', 'bar'];
        $randomNumber = mt_rand( 20, 90);
        $campus = $this->manager->getRepository(Campus::class)->findAll();
        $states = $this->manager->getRepository(State::class)->findAll();
        $place = new Place();


        $trip = new Trip();
        $trip->setName($this->faker->randomElement($activities))
            ->setCampus($this->faker->randomElement($campus))
            ->setDateStartHour($this->faker->dateTime)
            ->setDuration($randomNumber)
            ->setDateLimitRegistration($this->faker->dateTime)
            ->setNbMaxRegistration($randomNumber)
            ->setTripDescription($this->faker->text(20))
            ->setState($this->faker->randomElement($states))
            ->setPlace($place);





        $this->manager->persist($trip);
        $this->manager->flush();
    }

}