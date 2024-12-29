<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Entity\FournisseurAdresse;
use App\Form\FournisseurType;
use App\Repository\AdresseRepository;
use App\Repository\FournisseurAdresseRepository;
use App\Repository\FournisseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/fournisseur')]
// #[Route('/fournisseur')]
class FournisseurController extends AbstractController
{
    #[Route('/', name: 'app_fournisseur_index', methods: ['GET'])]
    public function index(FournisseurRepository $fournisseurRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) { // Redirige vers la route de la page d'accueil 
            return $this->redirectToRoute('app_main');
        }
        return $this->render('fournisseur/index.html.twig', [
            'fournisseurs' => $fournisseurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_fournisseur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) { // Redirige vers la route de la page d'accueil 
            return $this->redirectToRoute('app_main');
        }
        $fournisseur = new Fournisseur();
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fournisseur);
            $entityManager->flush();

            return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fournisseur/new.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fournisseur_show', methods: ['GET'])]
    public function show(#[MapEntity(mapping: ['id' => 'id'])] Fournisseur $fournisseur): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) { // Redirige vers la route de la page d'accueil 
            return $this->redirectToRoute('app_main');
        }
        $adresseByFournisseur = $fournisseur->getFournisseurAdresses();
        // dd($adresseByFournisseur);

        return $this->render('fournisseur/show.html.twig', [
            'fournisseur' => $fournisseur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fournisseur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity(id: "id")] Fournisseur $fournisseur, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) { // Redirige vers la route de la page d'accueil 
            return $this->redirectToRoute('app_main');
        }
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fournisseur/edit.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fournisseur_delete', methods: ['POST'])]
    public function delete(Request $request, #[MapEntity(id: "id")] Fournisseur $fournisseur, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) { // Redirige vers la route de la page d'accueil 
            return $this->redirectToRoute('app_main');
        }
        if ($this->isCsrfTokenValid('delete' . $fournisseur->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($fournisseur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/adresses', name: 'app_fournisseur_show_adresses', methods: ['GET'])]
    public function showAdresses(
        #[MapEntity(mapping: ['id' => 'id'])] Fournisseur $fournisseur,
        FournisseurAdresseRepository $fournisseurAdresseRepository,
        AdresseRepository $adresseRepository
    ): Response {
        if (!$this->isGranted('ROLE_ADMIN')) { // Redirige vers la route de la page d'accueil 
            return $this->redirectToRoute('app_main');
        }

        $fournisseurAdresses = $fournisseur->getFournisseurAdresses();
        $fournisseurAdresseIds = [];
        $fournisseurAdress = [];
        foreach ($fournisseurAdresses as $oneFournisseurAdresse) {
            $fournisseurAdresseIds[] = $oneFournisseurAdresse->getAdresse()->getId();
            $fournisseurAdress[] = $adresseRepository->find($oneFournisseurAdresse->getAdresse()->getId());
        }

        return $this->render('fournisseur/show_adresses.html.twig', [
            'adresses' => $fournisseurAdress,
            'fournisseur' => $fournisseur,
        ]);
    }
}
