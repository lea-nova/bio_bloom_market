<?php

namespace App\Tests;

use App\Entity\Categorie;
use PHPUnit\Framework\TestCase;

class CategorieTest extends TestCase
{
    public function testSomething(): void
    {
        $categorie = new Categorie();
        $categorie->setNom("Légumes");
        $categorie->setSlug("legumes");
        $categorie->setDescription("Une variété de légumes de saison");
        $this->assertEquals("Légumes", $categorie->getNom());
        $this->assertEquals("legumes", $categorie->getSlug());
        $this->assertEquals("Une variété de légumes de saison", $categorie->getDescription());
        $this->assertTrue(true);
    }
}
