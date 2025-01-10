<?php

namespace App\Tests\Controller;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProduitControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/produit/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Produit::class);

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
        self::assertPageTitleContains('Produit index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'produit[nom]' => 'Testing',
            'produit[description]' => 'Testing',
            'produit[slug]' => 'Testing',
            'produit[image]' => 'Testing',
            'produit[isVisible]' => 'Testing',
            'produit[stock]' => 'Testing',
            'produit[createdAt]' => 'Testing',
            'produit[updatedAt]' => 'Testing',
            'produit[marque]' => 'Testing',
            'produit[fournisseur]' => 'Testing',
            'produit[categorie]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Produit();
        $fixture->setNom('My Title');
        $fixture->setDescription('My Title');
        $fixture->setSlug('My Title');
        $fixture->setImage('My Title');
        $fixture->setIsVisible('My Title');
        $fixture->setStock('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setMarque('My Title');
        $fixture->setFournisseur('My Title');
        $fixture->setCategorie('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Produit');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Produit();
        $fixture->setNom('Value');
        $fixture->setDescription('Value');
        $fixture->setSlug('Value');
        $fixture->setImage('Value');
        $fixture->setIsVisible('Value');
        $fixture->setStock('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setMarque('Value');
        $fixture->setFournisseur('Value');
        $fixture->setCategorie('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'produit[nom]' => 'Something New',
            'produit[description]' => 'Something New',
            'produit[slug]' => 'Something New',
            'produit[image]' => 'Something New',
            'produit[isVisible]' => 'Something New',
            'produit[stock]' => 'Something New',
            'produit[createdAt]' => 'Something New',
            'produit[updatedAt]' => 'Something New',
            'produit[marque]' => 'Something New',
            'produit[fournisseur]' => 'Something New',
            'produit[categorie]' => 'Something New',
        ]);

        self::assertResponseRedirects('/produit/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getSlug());
        self::assertSame('Something New', $fixture[0]->getImage());
        self::assertSame('Something New', $fixture[0]->getIsVisible());
        self::assertSame('Something New', $fixture[0]->getStock());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getMarque());
        self::assertSame('Something New', $fixture[0]->getFournisseur());
        self::assertSame('Something New', $fixture[0]->getCategorie());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Produit();
        $fixture->setNom('Value');
        $fixture->setDescription('Value');
        $fixture->setSlug('Value');
        $fixture->setImage('Value');
        $fixture->setIsVisible('Value');
        $fixture->setStock('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setMarque('Value');
        $fixture->setFournisseur('Value');
        $fixture->setCategorie('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/produit/');
        self::assertSame(0, $this->repository->count([]));
    }
}
