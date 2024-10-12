<?php

namespace App\Tests;

use App\Kernel;
use App\Entity\User;
use DateTimeInterface;
use Doctrine\DBAL\Types\DateImmutableType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;
use App\DataFixtures\UserFixtures;


class UserTest extends KernelTestCase
{

    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $loader = $container->get('doctrine.fixtures.loader');
        // $executor = $container->get('doctrine.fixtures.executor');

        $loader->addFixture(new UserFixtures());
        // $executor->execute($loader->getFixtures(), true);
    }

    public function testUserCreation()
    {
        // Démarrer le noyau symfony
        self::bootKernel();
        // Récupérer le conteneur de services 
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        // Récupérer tous les utilisateurs depuis la base de données


        // Créer une nouvelle instance de l'entité User
        $user = new User();
        $user->setEmail('letest2@bloom.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword('Letest1234*');
        $user->setNom('Test');
        $user->setPrenom('Le');
        $user->setTelephone('0302010405');
        $user->setDateNaissance(new \DateTimeImmutable('2000-01-01'));
        $user->setFideliteClient(false);
        $user->setPrefAchat(null);
        $user->setUuid(Uuid::v4());

        $user->setCreatedAt(new \DateTimeImmutable());

        // Ajouter d'autres champs nécessaires
        // $user->setName('John Doe');
        // $user->setEmail('john@example.com');
        // etc...

        // Persister et flush l'entité
        $entityManager->persist($user);
        $entityManager->flush();

        // Récupérer l'utilisateur depuis la base de données
        $userFromDb = $entityManager->getRepository(User::class)->find($user->getId());

        // Assertions pour vérifier que l'utilisateur a été créé avec succès
        $this->assertNotNull($userFromDb);
        $this->assertEquals($user->getUuid(), $userFromDb->getUuid());
        $this->assertEquals($user->getCreatedAt(), $userFromDb->getCreatedAt());
        $this->assertEquals($user->getDateNaissance(), $userFromDb->getDateNaissance());
        $this->assertEquals($user->getEmail(), $userFromDb->getEmail());
        $this->assertEquals($user->getNom(), $userFromDb->getNom());
        $this->assertEquals($user->getPrenom(), $userFromDb->getPrenom());
        $this->assertEquals($user->getTelephone(), $userFromDb->getTelephone());

        // Ajouter d'autres assertions selon les champs définis
    }
}
