<?php

namespace App\Controller;

use App\Entity\LignePanier;
use App\Form\LignePanierType;
use App\Repository\LignePanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ligne/panier')]
final class LignePanierController extends AbstractController
{
    #[Route(name: 'app_ligne_panier_index', methods: ['GET'])]
    public function index(LignePanierRepository $lignePanierRepository): Response
    {
        return $this->render('ligne_panier/index.html.twig', [
            'ligne_paniers' => $lignePanierRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ligne_panier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lignePanier = new LignePanier();
        $form = $this->createForm(LignePanierType::class, $lignePanier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lignePanier);
            $entityManager->flush();

            return $this->redirectToRoute('app_ligne_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ligne_panier/new.html.twig', [
            'ligne_panier' => $lignePanier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ligne_panier_show', methods: ['GET'])]
    public function show(LignePanier $lignePanier): Response
    {
        return $this->render('ligne_panier/show.html.twig', [
            'ligne_panier' => $lignePanier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ligne_panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LignePanier $lignePanier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LignePanierType::class, $lignePanier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ligne_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ligne_panier/edit.html.twig', [
            'ligne_panier' => $lignePanier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ligne_panier_delete', methods: ['POST'])]
    public function delete(Request $request, LignePanier $lignePanier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lignePanier->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($lignePanier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ligne_panier_index', [], Response::HTTP_SEE_OTHER);
    }
}
