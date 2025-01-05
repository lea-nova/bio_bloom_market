<?php

namespace App\Tests\Controller;

use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CategorieControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/categorie/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Categorie::class);

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
        self::assertPageTitleContains('Categorie index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'categorie[nom]' => 'Testing',
            'categorie[slug]' => 'Testing',
            'categorie[description]' => 'Testing',
            'categorie[createdAt]' => 'Testing',
            'categorie[updatedAt]' => 'Testing',
            'categorie[isVisible]' => 'Testing',
            'categorie[produits]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Categorie();
        $fixture->setNom('My Title');
        $fixture->setSlug('My Title');
        $fixture->setDescription('My Title');
        // $fixture->setCreatedAt('My Title');
        // $fixture->setUpdatedAt('My Title');
        // $fixture->setIsVisible('My Title');
        // $fixture->setProduits('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Categorie');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Categorie();
        $fixture->setNom('Value');
        $fixture->setSlug('Value');
        $fixture->setDescription('Value');
        // $fixture->setCreatedAt('Value');
        // $fixture->setUpdatedAt('Value');
        // $fixture->setIsVisible('Value');
        // $fixture->setProduits('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'categorie[nom]' => 'Something New',
            'categorie[slug]' => 'Something New',
            'categorie[description]' => 'Something New',
            'categorie[createdAt]' => 'Something New',
            'categorie[updatedAt]' => 'Something New',
            'categorie[isVisible]' => 'Something New',
            'categorie[produits]' => 'Something New',
        ]);

        self::assertResponseRedirects('/categorie/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getSlug());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getIsVisible());
        self::assertSame('Something New', $fixture[0]->getProduits());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Categorie();
        $fixture->setNom('Value');
        $fixture->setSlug('Value');
        $fixture->setDescription('Value');
        // $fixture->setCreatedAt('Value');
        // $fixture->setUpdatedAt('Value');
        // $fixture->setIsVisible('Value');
        // $fixture->setProduits('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/categorie/');
        self::assertSame(0, $this->repository->count([]));
    }
}
