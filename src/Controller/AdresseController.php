<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Fournisseur;
use App\Form\AdresseType;
use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

// #[Route('/adresse')]
class AdresseController extends AbstractController
{
    #[Route('admin/adresse', name: 'app_adresse_index', methods: ['GET', 'POST'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) { // Redirige vers la route de la page d'accueil 
            return $this->redirectToRoute('app_main');
        }
        $adresses = $entityManager
            ->getRepository(Adresse::class)
            ->findAll();

        return $this->render('adresse/index.html.twig', [
            'adresses' => $adresses,
        ]);
    }

    #[Route('adresse/new', name: 'app_adresse_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adresse = new Adresse();
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adresse);
            $entityManager->flush();

            return $this->redirectToRoute('app_adresse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adresse/new.html.twig', [
            'adresse' => $adresse,
            'form' => $form,
        ]);
    }

    #[Route('adresse/{ulid}', name: 'app_adresse_show', methods: ['GET'])]
    public function show(#[MapEntity(mapping: ['ulid' => "ulid"])] Adresse $adresse, string $ulid, AdresseRepository $adresseRepository): Response
    {
        $adresse = $adresseRepository->findOneBy(['ulid' => $ulid]);


        return $this->render('adresse/show.html.twig', [
            'adresse' => $adresse,
        ]);
    }

    #[Route('adresse/{ulid}/edit', name: 'app_adresse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity(mapping: ['ulid' => "ulid"])] Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_adresse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adresse/edit.html.twig', [
            'adresse' => $adresse,
            'form' => $form,
        ]);
    }

    #[Route('adresse/{ulid}', name: 'app_adresse_delete', methods: ['POST'])]
    public function delete(Request $request, #[MapEntity(mapping: ['ulid' => "ulid"])] Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $adresse->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($adresse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_adresse_index', [], Response::HTTP_SEE_OTHER);
    }
}
