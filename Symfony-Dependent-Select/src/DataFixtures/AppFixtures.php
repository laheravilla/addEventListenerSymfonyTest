<?php

namespace App\DataFixtures;

use App\Entity\Continent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use App\Entity\Client;

class AppFixtures extends Fixture
{
    const CONTINENTS = ['America', 'Europe', 'Africa', 'Asia', 'Australia'];

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');

        for ($i = 1; $i <= 20; $i++) {
            $client = new Client();
            $client->setName($faker->name);
            $manager->persist($client);
        }

        foreach (self::CONTINENTS as $continentName) {
            $continent = new Continent();
            $continent->setName($continentName);
            $manager->persist($continent);
        }

        $manager->flush();
    }
}
