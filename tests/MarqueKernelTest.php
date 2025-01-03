<?php

namespace App\Tests;

use App\Entity\Marque;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MarqueKernelTest extends KernelTestCase
{

    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        // $container = self::$container;;
        $validator = self::getContainer()->get(ValidatorInterface::class);

        $marque = new Marque();
        $marque->setNom('');
        // $errors = $validator->validate($marque);
        // $this->assertCount(1, $errors);
        $this->expectExceptionMessage("Le nom ne peux être vide.");

        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }
}
