<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use App\Service\UploadFileService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


// #[Route('/marque')]
final class MarqueController extends AbstractController
{
    #[Route('/marque', name: 'app_marque_index', methods: ['GET'])]
    public function index(MarqueRepository $marqueRepository): Response
    {
        if ($this->isGranted("ROLE_ADMIN")) {
            $marques = $marqueRepository->findAll();
        } else {
            $marques = $marqueRepository->findBy(['active' => true]);
        }
        return $this->render('marque/index.html.twig', [
            'marques' => $marques,
        ]);
    }

    #[Route('/admin/marque/new', name: 'app_marque_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/marques')] string $marquesDirectory,
        UploadFileService $uploadFileService
    ): Response {
        if (!$this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('app_marque_index');
        }
        $marque = new Marque();
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            if ($marque) {
                $logoFile = $form->get('logo')->getData();

                if ($logoFile) {

                    // $originalLogoFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // $safeFileName = $slugger->slug($originalLogoFilename);
                    // $newLogoFileName = $safeFileName . '-' . uniqid() . '.' . $logoFile->guessExtension();
                    $fileToSend = $uploadFileService->uploadFile($logoFile);
                    // dd($newLogoFileName);
                    if ($fileToSend) {
                        // $logoFile->move($marquesDirectory, $newLogoFileName);
                        // $marque->setLogo($newLogoFileName);
                        $logoFile->move($marquesDirectory, $fileToSend);
                        $marque->setLogo($fileToSend);
                    }
                }
            }

            $nomCategorie = $marque->getNom();
            $nomCategorieSlug = $slugger->slug($nomCategorie);
            $marque->setSlug(strtolower(($nomCategorieSlug)));
            $entityManager->persist($marque);
            $entityManager->flush();

            return $this->redirectToRoute('app_marque_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('marque/new.html.twig', [
            'marque' => $marque,
            'form' => $form,
        ]);
    }

    #[Route('/marque/{slug}', name: 'app_marque_show', methods: ['GET'])]
    public function show(#[MapEntity(mapping: ['slug' => 'slug'])] Marque $marque): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && $marque->isActive() === false) {
            return $this->redirectToRoute("app_marque_index");
        }

        $produitVisible = [];
        if ($marque->getProduits()) {
            foreach ($marque->getProduits() as $produit) {
                if ($produit->isVisible()) {
                    $produitVisible = $produit->isVisible();
                }
            }
        }
        return $this->render('marque/show.html.twig', [
            'produitVisible' => $produitVisible,
            'marque' => $marque,
        ]);
    }

    #[Route('admin/marque/{slug}/edit', name: 'app_marque_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        #[MapEntity(mapping: ['slug' => 'slug'])] Marque $marque,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/marques')] string $marquesDirectory,
        UploadFileService $uploadFileService
    ): Response {
        if (!$this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute("app_marque_index");
        }
        $logoMarque = $marque->getLogo();
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);
        // $pathInPublic = 'uploads/marques/';

        if ($form->isSubmitted() && $form->isValid()) {

            $logoFile = $form->get('logo')->getData();
            // dd($logoFile);
            if ($logoFile) {
                $originalLogoFilename = pathinfo(
                    $logoFile->getClientOriginalName(),
                    PATHINFO_FILENAME
                );

                // $safeFileName = $slugger->slug($originalLogoFilename);
                // $newLogoFileName = $safeFileName . '-' . uniqid() . '.' . $logoFile->guessExtension();
                $fileToSend = $uploadFileService->uploadFile($logoFile);
                // dd($fileToSend);
                if ($fileToSend) {

                    $logoFile->move($marquesDirectory, $fileToSend);
                    $marque->setLogo($fileToSend);
                    $marqueFilePath = 'uploads/marques/' . $logoMarque;
                    // dd($marqueFilePath);
                    if ($logoMarque) {
                        unlink($marqueFilePath);
                    }
                }
            } else {
                $marque->setLogo($logoMarque);
            }
            $marque->setActive($form->get('active')->getData());
            $nomMarque = $form->get('nom')->getData();
            $nomSlugMarque = $slugger->slug($nomMarque);
            $marque->setSlug(strtolower($nomSlugMarque));
            $marque->setUpdatedAt(new DateTimeImmutable());

            $entityManager->persist($marque);
            $entityManager->flush();

            return $this->redirectToRoute('app_marque_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('marque/edit.html.twig', [
            'marque' => $marque,
            'form' => $form,
        ]);
    }

    #[Route('/admin/marque/{id}', name: 'app_marque_delete', methods: ['POST'])]
    public function delete(Request $request, Marque $marque, EntityManagerInterface $entityManager, UploadFileService $uploadFileService): Response
    {
        if (!$this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('app_marque_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete' . $marque->getId(), $request->getPayload()->getString('_token'))) {
            $marqueFilePath = 'uploads/marques/' . $marque->getLogo();
            $fileToRemove = $uploadFileService->removeFile($marqueFilePath);
            if ($fileToRemove) {

                $entityManager->remove($marque);
                $entityManager->flush();
            }
            $this->addFlash('fail', 'Le fichier ne s\'est pas supprimé correctement');
        }

        return $this->redirectToRoute('app_marque_index', [], Response::HTTP_SEE_OTHER);
    }
}
