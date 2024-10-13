<?php

namespace App\Tests;

use App\Kernel;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Adresse;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
// use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Ulid;

class AdresseTest extends KernelTestCase
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
        // $loader = $container->get('doctrine.fixtures.loader');
        // $executor = $container->get('doctrine.fixtures.executor');

        // $loader->addFixture(new UserFixtures());
        // $executor->execute($loader->getFixtures(), true);
    }

    public function testAdresseCreation(): void
    {
        // Démarrer le noyau symfony
        self::bootKernel();
        // Récupérer le conteneur de services 
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);

        $adresse = new Adresse();
        $adresse->setLigne1('12 Rue du test');
        $adresse->setLigne2('Appartement 2 étage 3');
        $adresse->setCodePostal(69000);
        $adresse->setVille('Lyon');
        $adresse->setPays('France');
        $adresse->setUlid();
        $adresse->setCreatedAt(new \DateTimeImmutable('2000-01-01'));
        $entityManager->persist($adresse);
        // dd($adresse);
        $entityManager->flush();

        $adresseFromDb = $entityManager->getRepository(Adresse::class)->find($adresse->getId());
        $this->assertNotNull($adresseFromDb);
        $this->assertEquals($adresse->getLigne1(), $adresseFromDb->getLigne1());
        $this->assertEquals($adresse->getLigne2(), $adresseFromDb->getLigne2());
        $this->assertEquals($adresse->getCodePostal(), $adresseFromDb->getCodePostal());
        $this->assertEquals($adresse->getVille(), $adresseFromDb->getVille());
        $this->assertEquals($adresse->getPays(), $adresseFromDb->getPays());
        $this->assertEquals($adresse->getUlid(), $adresseFromDb->getUlid());
        $this->assertEquals($adresse->getCreatedAt(), $adresseFromDb->getCreatedAt());
    }
}
