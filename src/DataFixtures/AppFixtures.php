<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Place;
use App\Entity\Trip;
use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Length;

class AppFixtures extends Fixture
{

    private $encoder;
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
        $this->loadCity($manager);
        $this->loadPlace($manager);

    }

    private function addCampus()
    {

    public function loadCity(ObjectManager $manager){

        for($i = 1; $i <= 10; $i++){

            $newCity = new City();

            $newCity
                ->setName($this->faker->city)
                ->setPostalCode($this->faker->countryCode);

        foreach (self::DEFAULT_CAMPUS as $campusName) {
            $campus = new Campus();
            $campus->setName($campusName);
            $this->manager->persist($campus);
        }
            $manager->persist($newCity);
        }

        $this->manager->flush();
        $manager->flush();


    }


    private function addParticipant()
    {
        for ($i = 1; $i <= 10; $i++) {
            $participant = new Participant();
    public function loadPlace(ObjectManager $manager){

        $tabPlace = ['cinema', 'bar', 'piscine', 'chez mémé', 'travail', 'ENI'];

        for($i = 1; $i <= 10; $i++){

            $newPlace = new Place();
            $tabCity = $manager->getRepository(City::class)->findAll();

            $newPlace
                ->setName($this->faker->randomElement($tabPlace))
                ->setCity($this->faker->randomElement($tabCity))
                ->setStreet($this->faker->streetName)
            ;

            $manager->persist($newPlace);
        }

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
        $manager->flush();

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