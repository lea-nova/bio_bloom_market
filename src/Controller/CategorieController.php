<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

// #[Route('/categorie')]
final class CategorieController extends AbstractController
{
    #[Route('admin/categorie', name: 'app_categorie_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) { // Redirige vers la route de la page d'accueil 
            return $this->redirectToRoute('app_main');
        }
        return $this->render('categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/admin/categorie/new', name: 'app_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,  SluggerInterface $slugger): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) { // Redirige vers la route de la page d'accueil 
            return $this->redirectToRoute('app_main');
        }
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugCategorie = $slugger->slug($categorie->getNom());
            // dump($slugCategorie);
            $categorie->setSlug(strtolower($slugCategorie));
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    // #[Route('/categorie/{id}', name: 'app_categorie_show', methods: ['GET'])]
    #[Route('/categorie/{slug}', name: 'app_categorie_show', methods: ['GET'])]
    public function show(#[MapEntity(mapping: ['slug' => "slug"])] Categorie $categorie, SluggerInterface $slugger): Response
    {
        // $slugCategorie = $slugger->slug($categorie->getNom());
        // $categorie->setSlug(strtolower($slugCategorie));
        // dump($slugCategorie);
        // dd($categorie);

        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/admin/categorie/{slug}/edit', name: 'app_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity(mapping: ['slug' => "slug"])] Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) { // Redirige vers la route de la page d'accueil 
            return $this->redirectToRoute('app_main');
        }
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/admin/categorie/{id}', name: 'app_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) { // Redirige vers la route de la page d'accueil 
            return $this->redirectToRoute('app_main');
        }
        if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
