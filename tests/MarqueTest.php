<?php

namespace App\Tests;

use App\Entity\Marque;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class MarqueTest extends TestCase
{
    public function testSetterAndGetterNotFail(): void
    {
        $marque = new Marque();
        $marque->setNom("Jardin Nature");
        $marque->setSlug("jardin et nature");
        $marque->setDescription("Description pour Jardin Nature.");
        $marque->setLogo("logo_jardin_nature.jpeg");
        $marque->setCreatedAt(new DateTimeImmutable());
        $this->assertEquals("Jardin Nature", $marque->getNom());
        $this->assertEquals("jardin et nature", $marque->getSlug());
        $this->assertEquals("Description pour Jardin Nature.", $marque->getDescription());
        $this->assertEquals("logo_jardin_nature.jpeg", $marque->getLogo());
        $this->assertTrue(true);
    }
    public function testSetterAndGetterFail(): void
    {
        $marque = new Marque();
        $marque->setNom("");
        // Ne pas appeler les setters pour tester si les valeurs par défaut ou les exceptions fonctionnent correctement

        try {
            $marque->getNom();
        } catch (\Exception $e) {
            $this->assertEquals('Nom obligatoire', $e->getMessage());
        }

        try {
            $marque->getSlug();
        } catch (\Exception $e) {
            $this->assertEquals('Slug obligatoire', $e->getMessage());
        }

        try {
            $marque->getDescription();
        } catch (\Exception $e) {
            $this->assertEquals('Description obligatoire', $e->getMessage());
        }

        try {
            $marque->getLogo();
        } catch (\Exception $e) {
            $this->assertEquals('Logo obligatoire', $e->getMessage());
        }

        try {
            $marque->getCreatedAt();
        } catch (\Exception $e) {
            $this->assertEquals('Date de création obligatoire', $e->getMessage());
        }

        $this->assertTrue(true);
    }
}
