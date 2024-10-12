<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Uid\Uuid;


class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setPassword($faker->password(), 8);
            $user->setNom($faker->firstName());
            $user->setPrenom($faker->lastName());
            $user->setTelephone($faker->phoneNumber());
            $user->setDateNaissance(new \DateTimeImmutable($faker->date('Y-m-d')));
            $user->setUuid(Uuid::v4());
            $user->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($user);
            $manager->flush();
        }
    }
}
