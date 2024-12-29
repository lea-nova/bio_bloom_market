<?php

namespace App\Tests;

use App\Entity\Fournisseur;
use PHPUnit\Framework\TestCase;

class FournisseurTest extends TestCase
{
    public function testFournisseur(): void
    {
        $fournisseur = new Fournisseur();
        $fournisseur->setNom('Test Fournisseur');
        $fournisseur->setTelephone("123456789");
        $fournisseur->setMail("test@fournisseur.com");
        $fournisseur->setService("Service test");
        $this->assertEquals('Test Fournisseur', $fournisseur->getNom());
        $this->assertEquals("123456789", $fournisseur->getTelephone());
        $this->assertEquals("test@fournisseur.com", $fournisseur->getMail());
        $this->assertEquals("Service test", $fournisseur->getService());

        // $this->assertTrue(true);
    }
}
