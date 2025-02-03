<?php

namespace App\Tests\Controller;

use App\Entity\LignePanier;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class LignePanierControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/ligne/panier/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(LignePanier::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('LignePanier index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'ligne_panier[quantite]' => 'Testing',
            'ligne_panier[prixTotal]' => 'Testing',
            'ligne_panier[produit]' => 'Testing',
            'ligne_panier[panier]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new LignePanier();
        $fixture->setQuantite('My Title');
        $fixture->setPrixTotal('My Title');
        $fixture->setProduit('My Title');
        $fixture->setPanier('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('LignePanier');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new LignePanier();
        $fixture->setQuantite('Value');
        $fixture->setPrixTotal('Value');
        $fixture->setProduit('Value');
        $fixture->setPanier('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'ligne_panier[quantite]' => 'Something New',
            'ligne_panier[prixTotal]' => 'Something New',
            'ligne_panier[produit]' => 'Something New',
            'ligne_panier[panier]' => 'Something New',
        ]);

        self::assertResponseRedirects('/ligne/panier/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getQuantite());
        self::assertSame('Something New', $fixture[0]->getPrixTotal());
        self::assertSame('Something New', $fixture[0]->getProduit());
        self::assertSame('Something New', $fixture[0]->getPanier());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new LignePanier();
        $fixture->setQuantite('Value');
        $fixture->setPrixTotal('Value');
        $fixture->setProduit('Value');
        $fixture->setPanier('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/ligne/panier/');
        self::assertSame(0, $this->repository->count([]));
    }
}
