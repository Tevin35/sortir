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
    private $manager;
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        $campus = new Campus();
        $campus->setName('Niort');
        $manager->persist($campus);
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
}