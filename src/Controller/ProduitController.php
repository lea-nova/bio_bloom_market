<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Service\UploadFileService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

// #[Route('/produit')]
final class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $produits = $produitRepository->findAll();
        } else {
            $produits = $produitRepository->findBy(['visible' => true]);
        }
        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/admin/produit/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        #[Autowire('%kernel.project_dir%/public/uploads/produits')] string $produitsDirectory,
        UploadFileService $uploadFileService,
        SluggerInterface $slugger
    ): Response {

        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_produit_index');
        }
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($produit) {
                $imageProduit = $form->get('image')->getData();
                // dd($imageProduit);
                if ($imageProduit) {
                    // dd($imageProduit);
                    $fileToSend = $uploadFileService->uploadFile($imageProduit);
                    // dd($fileToSend);
                    if ($fileToSend) {
                        $imageProduit->move($produitsDirectory, $fileToSend);
                        $produit->setImage($fileToSend);
                    }
                }
            }
            $nomProduit = $produit->getNom();
            $nomProduitSlug = $slugger->slug($nomProduit);
            $produit->setSlug(strtolower($nomProduitSlug));
            $produit->setCreatedAt(new DateTimeImmutable());
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/produit/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/admin/produit/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Produit $produit,
        EntityManagerInterface $entityManager,
        #[Autowire('%kernel.project_dir%/public/uploads/produits')] string $produitsDirectory,
        UploadFileService $uploadFileService,
        SluggerInterface $slugger
    ): Response {
        // dd($request);
        if (!$this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('app_produit_show', ['id' => $produit->getId()]);
        }
        $imageProduitInDb = $produit->getImage();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($produit) {
                $imageProduit = $form->get('image')->getData();
                // dd($imageProduit);
                if ($imageProduit) {
                    // dd($imageProduit);
                    $fileToSend = $uploadFileService->uploadFile($imageProduit);
                    // dd($fileToSend);
                    if ($fileToSend) {
                        $imageProduit->move($produitsDirectory, $fileToSend);
                        $produit->setImage($fileToSend);
                        $produitFilePath = 'uploads/produits/' . $imageProduitInDb;
                        if ($imageProduitInDb) {
                            unlink($produitFilePath);
                        }
                    }
                }
            } else {
                $produit->setImage($imageProduitInDb);
            }
            if ($imageProduitInDb) {
                $produit->setImage($imageProduitInDb);
            }

            $produit->setVisible($form->get('visible')->getData());

            $produit->setUpdatedAt(new DateTimeImmutable());
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/admin/produit/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Produit $produit,
        EntityManagerInterface $entityManager,
        UploadFileService $uploadFileService
    ): Response {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute("app_produit_index");
        }

        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->getPayload()->getString('_token'))) {
            $produitFilePath = 'uploads/produits/' . $produit->getImage();
            if ($produit->getImage()) {

                // $fileToRemove =
                $uploadFileService->removeFile($produitFilePath);
            }

            $entityManager->remove($produit);
            $entityManager->flush();
            // }
            $this->addFlash('fail', 'Le fichier ne s\'est pas supprimé correctement');
        }


        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
    public function toggleVisibleProduct() {}
}
