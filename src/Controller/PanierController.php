<?php

namespace App\Controller;

use App\Entity\LignePanier;
use App\Entity\Panier;
use App\Form\PanierQuantiteType;
use App\Form\PanierType;
use App\Repository\LignePanierRepository;
use App\Repository\PanierRepository;
use DateTime;
use DateTimeImmutable;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/panier')]
final class PanierController extends AbstractController
{
    #[Route(name: 'app_panier_index', methods: ['GET'])]
    // public function index(PanierRepository $panierRepository): Response
    // {
    //     return $this->render('panier/index.html.twig', [
    //         'paniers' => $panierRepository->findAll(),
    //     ]);
    // }

    // #[Route('/new', name: 'app_panier_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $panier = new Panier();
    //     $form = $this->createForm(PanierType::class, $panier);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($panier);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('panier/new.html.twig', [
    //         'panier' => $panier,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/', name: 'app_panier_show', methods: ['GET'])]
    public function show(PanierRepository $panierRepository, EntityManagerInterface $entityManager): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $panier = $panierRepository->findOneBy(['user' => $this->getUser()]);
        if (!$panier) {
            $panier = new Panier();
            $panier->setUser($this->getUser());
            $panier->setCreatedAt(new DateTimeImmutable());
            $entityManager->persist($panier);
        }
        $entityManager->flush();
        $formQuantite = [];
        foreach ($panier->getItems() as $item) {
            // dd($item->getQuantite());
            $formQuantite[$item->getId()] = $this->createForm(PanierQuantiteType::class, ['quantite' => $item->getQuantite()])->createView();
        }
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
            'formQuantite' => $formQuantite
        ]);
    }


    #[Route('/panier/update/{id}', name: 'panier_update_quantite', methods: ['POST'])]
    public function updateQuantite(Request $request, int $id, LignePanierRepository $lignePanierRepository, EntityManagerInterface $entityManager): Response
    {
        // dd($id);
        $lignePanier = $lignePanierRepository->find($id);
        // dump($lignePanier);
        $form = $this->createForm(PanierQuantiteType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $valeurQuantite = $form->get('quantite')->getData();
            $panier = $lignePanier->getPanier();
            if ($valeurQuantite === 0) {
                $panier->removeItem($lignePanier);
                // $entityManager->remove($lignePanier);
            } else {

                $lignePanier->setQuantite($valeurQuantite);
                $lignePanier->getPanier();
                $panier->setUpdatedAt(new DateTimeImmutable());
                $entityManager->persist($panier);
                $entityManager->persist($lignePanier);
            }
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_panier_show');
    }

    // #[Route('/{id}/edit', name: 'app_panier_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(PanierType::class, $panier);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('panier/edit.html.twig', [
    //         'panier' => $panier,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_panier_delete', methods: ['POST'])]
    // public function delete(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete' . $panier->getId(), $request->getPayload()->getString('_token'))) {
    //         $entityManager->remove($panier);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    // }
}
