<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $ingredients = [];
        for ($i = 1; $i <= 10; $i++) {
            $ingredients[$i] = new Ingredient();
            $ingredients[$i]->setName('ingredient'. $i);
            $ingredients[$i]->setCostPrice(rand(1,10));
            $manager->persist( $ingredients[$i]);
        }


        $manager->flush();
    }
}
