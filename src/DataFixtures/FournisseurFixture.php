<?php

namespace App\DataFixtures;

use App\Entity\Fournisseur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;

class FournisseurFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 20; $i++) {

            $fournisseur = new Fournisseur();
            $fournisseur->setNom($faker->company());
            $fournisseur->setTelephone($faker->phoneNumber());
            $fournisseur->setMail($faker->email());
            $fournisseur->setService("Aliments bio divers et variés.");
            $manager->persist($fournisseur);

            $manager->flush();
        }
    }
}
