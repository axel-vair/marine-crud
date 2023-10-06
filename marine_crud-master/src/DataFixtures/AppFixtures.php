<?php

namespace App\DataFixtures;

use App\Entity\Boat;
use App\Entity\Classe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(){
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < 10; $i++){
            $user = new User();
            $user->setFirstName($this->faker->firstName)
                ->setLastName($this->faker->lastName)
                ->setEmail($this->faker->email)
                ->setRoles(mt_rand(0, 1) ? ['ROLE_ADMIN'] : ['ROLE_USER'])
                ->setPassword('password');
            $manager->persist($user);

        }

        for($i = 0; $i < 10; $i++){
            $classe = new Classe();
            $classe->setWording($this->faker->word);
            $classes[] = $classe;
            $manager->persist($classe);
        }

        for($i = 0; $i < 10; $i++){
            $boat = new Boat;
            $boat->setName($this->faker->name)
                ->setLength(mt_rand(10, 300))
                ->setTonnage(mt_rand(10, 200))
                ->setIdentifier($this->faker->word)
                ->setClasse($classes[mt_rand(0, count($classes) -1)]);
            $manager->persist($boat);
        }
        $manager->flush();
    }
}
