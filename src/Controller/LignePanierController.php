<?php

namespace App\Controller;

use App\Entity\LignePanier;
use App\Entity\Produit;
use App\Form\LignePanierType;
use App\Repository\LignePanierRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ligne-panier')]
final class LignePanierController extends AbstractController
{
    // #[Route(name: 'app_ligne_panier_index', methods: ['GET'])]
    // public function index(LignePanierRepository $lignePanierRepository): Response
    // {
    //     return $this->render('ligne_panier/index.html.twig', [
    //         'ligne_paniers' => $lignePanierRepository->findAll(),
    //     ]);
    // }

    #[Route('/add/{produitId}', name: 'app_ligne_panier_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        $produitId,
        ProduitRepository $produitRepository,
        PanierRepository $panierRepository
    ): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $produit = $produitRepository->find($produitId);
        $user = $this->getUser();
        $panier = $panierRepository->findOneBy(['user' => $this->getUser()]);
        // dump($produit);

        $lignesPanier = $panier->getItems();
        $produitPanierId = [];
        $produitPanier = [];

        // Si le produit n'est pas déjà au moins une fois dans le panier alors nouvelle ligne : 
        // Si le produit est absent de la collection. 
        foreach ($lignesPanier as $produitByLigne) {
            $produitPanierId[] = $produitByLigne->getProduit()->getId();
            // $produitPanier[] = $produitRepository->find($produitByLigne->getProduit()->getId());
        }
        // dd(in_array($produit->getId(), $produitPanierId));
        if (!in_array($produit->getId(), $produitPanierId)) {
            $lignePanier = new LignePanier();

            $lignePanier->setQuantite(1);
            $lignePanier->setPrixTotal(00.00); // Pour l'instant, tant que j'ai pas les prix pour chaque produit.
            $lignePanier->setPanier($panier);
            $lignePanier->setProduit($produit);
            $lignePanier->setPrixTotal(00.00);
            $entityManager->persist($lignePanier);
            $entityManager->flush();
        } else {
            foreach ($panier->getItems() as $item) {
                if ($produit->getId() === $item->getProduit()->getId()) {

                    $item->setQuantite($item->getQuantite() + 1);
                    $entityManager->persist($item);
                    $entityManager->flush();
                    // return $this->redirectToRoute('app_produit_show', ['id' => $produit->getId()], Response::HTTP_SEE_OTHER);
                }
            }
            // foreach ($lignesPanier as $produitByLigne) {
            //     if ($produitByLigne === $produit) {
            //         dump($produit);
            //         dd($produitByLigne);
            //     }
            //     // $produitPanierId[] = $produitByLigne->getProduit()->getId();
            //     // $produitPanier[] = $produitRepository->find($produitByLigne->getProduit()->getId());

            // dd(in_array($produit->getId(), $produitPanierId));
            // $produit->setQuantite($->getQuantite() + 1);
            // $entityManager->persist($);
            // Si le produit est déjà dans le panier juste ajouter 1 dans la quantité. 
        }
        // dd(in_array($produit, $lignesPanier));
        // else{

        // }
        // dd($panier);

        return $this->redirectToRoute('app_produit_show', ['id' => $produit->getId()], Response::HTTP_SEE_OTHER);
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

    #[Route('/{produitId}', name: 'app_ligne_panier_delete', methods: ['POST'])]
    public function delete(Request $request, #[MapEntity(mapping: ['produitId' => 'id'])] LignePanier $lignePanier, EntityManagerInterface $entityManager, PanierRepository $panierRepository): Response
    {

        // dd($lignePanier->getId());
        // if ($this->isCsrfTokenValid('delete' . $lignePanier->getId(), $request->getPayload()->getString('_token'))) {
        $panier = $panierRepository->findOneBy(['user' => $this->getUser()]);
        // dd($panier->removeItem($lignePanier));
        $panier->removeItem($lignePanier);
        $entityManager->remove($lignePanier);
        $entityManager->flush();
        // }

        return $this->redirectToRoute('app_panier_show', [], Response::HTTP_SEE_OTHER);
    }
}
