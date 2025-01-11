<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Fournisseur;
use App\Entity\FournisseurAdresse;
use App\Form\AdresseType;
use App\Repository\FournisseurAdresseRepository;
use App\Repository\FournisseurRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\DateImmutableType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

// #[Route('/adresse')]
class FournisseurAdresseController extends AbstractController
{
    #[Route('admin/fournisseur/{id:fournisseur}/adresses', name: 'app_adresse_fournisseur_index', methods: ['POST'])]
    public function index(EntityManagerInterface $entityManager, $id): Response
    {


        if (!$this->isGranted('ROLE_ADMIN')) { // Redirige vers la route de la page d'accueil 
            return $this->redirectToRoute('app_main');
        }
        $adressesFournisseurId = $entityManager
            ->getRepository(FournisseurAdresse::class)
            ->findAll();
        $adresses = [];
        foreach ($adressesFournisseurId as $adresseFournisseurId) {
            $adresses = $entityManager->getRepository(Adresse::class)->find($adresseFournisseurId);
        }
        $fournisseur = $entityManager->getRepository(Fournisseur::class)->find($id);
        // dd($adresses);
        return $this->render('fournisseur_adresse/index.html.twig', [
            'adresses' => $adresses,
            'fournisseur' => $fournisseur
        ]);
    }

    #[Route('admin/fournisseur/{id}/adresse/new', name: 'app_adresse_fournisseur_new', methods: ['POST', 'GET'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        int $id,
        FournisseurRepository $fournisseurRepository
    ): Response {
        $currentFournisseur = $fournisseurRepository->find($id);
        $adresse = new Adresse();
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fournisseurAdresse = new FournisseurAdresse();
            $fournisseurAdresse->setAdresse($adresse);
            $fournisseurAdresse->setCreatedAt(new DateTimeImmutable());
            $fournisseurAdresse->setUpdatedAt(new DateTimeImmutable());
            $currentFournisseur->addFournisseurAdress($fournisseurAdresse);
            $entityManager->persist($fournisseurAdresse);
            $entityManager->persist($adresse);


            $entityManager->flush();
            return $this->redirectToRoute('app_fournisseur_show_adresses', ['id' => $currentFournisseur->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('adresse/new.html.twig', [
            'adresse' => $adresse,
            'form' => $form,
        ]);
    }

    #[Route('admin/fournisseur/{id}/adresse/{ulid}', name: 'app_adresse_fournisseur_show', methods: ['GET'])]
    public function show(#[MapEntity(mapping: ['ulid' => "ulid"])] Adresse $adresse): Response
    {


        return $this->render('adresse/show.html.twig', [
            'adresse' => $adresse,
        ]);
    }

    #[Route('admin/fournisseur/{id}/adresse/{ulid}/edit', name: 'app_adresse_fournisseur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity(mapping: ["ulid" => "ulid"])] Adresse $adresse, EntityManagerInterface $entityManager, int $id, string $ulid): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) { // Redirige vers la route de la page d'accueil 
            return $this->redirectToRoute('app_main');
        }
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adresse->setUpdatedAt(new DateTimeImmutable());
            $fournisseurAdresse = $adresse->getFournisseurAdresses();
            // dd($fournisseurAdresse);
            $adresseFournisseur = [];
            foreach ($fournisseurAdresse as $oneAdresseFournisseur) {
                if ($adresse === $oneAdresseFournisseur->getAdresse()) {
                    $oneAdresseFournisseur->setUpdatedAt(new DateTimeImmutable());
                }
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_fournisseur_show_adresses', ['id' => $id], Response::HTTP_SEE_OTHER);
        }


        return $this->render('adresse/edit.html.twig', [
            'adresse' => $adresse,
            'form' => $form,
        ]);
    }

    #[Route('admin/fournisseur/{id}/adresse/{idAdresse}', name: 'app_adresse_fournisseur_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        #[MapEntity(mapping: ["idAdresse" => "id"])] Adresse $adresse,
        EntityManagerInterface $entityManager,
        int $idAdresse,
        int $id
    ): Response {
        $currentFournisseur = $entityManager->getRepository(Fournisseur::class)->find($id);
        // dd($idAdresse);
        $thisFournisseurAdresse = $entityManager->getRepository(FournisseurAdresse::class)->findBy(['fournisseur' => $currentFournisseur, 'adresse' => $adresse]);

        if ($this->isCsrfTokenValid('delete' . $adresse->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($adresse);
            foreach ($thisFournisseurAdresse as $oneFournisseurAdresse) {
                if ($id === $oneFournisseurAdresse->getFournisseur()->getId() && $idAdresse === $oneFournisseurAdresse->getAdresse()->getId())
                    $entityManager->remove($oneFournisseurAdresse);
            }
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fournisseur_show_adresses', ['id' => $id], Response::HTTP_SEE_OTHER);
    }
}
