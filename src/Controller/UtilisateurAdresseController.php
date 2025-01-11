<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\User;
use App\Entity\UserAdresse;
use App\Form\AdresseType;
use App\Repository\AdresseRepository;
use App\Repository\UserAdresseRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
// use Faker\Core\Uuid;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Form\FormTypeInterface;

class UtilisateurAdresseController extends AbstractController
{
    #[Route('user/{uuid}/adresses', name: 'app_user_adresses', methods: ['GET'])]
    public function showAdresses(
        UserRepository $userRepository,
        #[MapEntity(mapping: ['uuid' => 'uuid'])] User $user,
    ): Response {
        if (empty($this->getUser()) || $user->getUuid() !== $this->getUser()->getUuid()) {

            return $this->redirectToRoute("app_main");
        }
        $currentUser = $this->getUser();

        $userAdresses = $user->getUserAdresses();
        $adressesByUser = [];
        foreach ($userAdresses as $userAdresse) {
            $adressesByUser[] = $userAdresse->getAdresse();
        }

        return $this->render('user_adresse/show_adresses.html.twig', [
            'user' => $currentUser,
            'adresses' => $adressesByUser
            // 'adresses' => $adresses,
        ]);
    }

    #[Route('user/{uuid}/adresses/new', name: 'app_user_adresses_new', methods: ['GET', 'POST'])]
    public function addAdresse(
        #[MapEntity(mapping: ['uuid' => 'uuid'])] User $user,
        string $uuid,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $currentUser = $this->getUser();
        if (empty($this->getUser()) || $user->getUuid() !== $this->getUser()->getUuid()) {

            return $this->redirectToRoute("app_main");
        }
        // if (!$currentUser) {
        //     throw $this->createNotFoundException('Utilisateur non connecté');
        // }


        // Créer une nouvelle instance d'Adresse
        $adresse = new Adresse();
        // Créer le formulaire
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newAdresse = $form->getData();

            $adresseExiste = $entityManager->getRepository(Adresse::class)->findOneBy([
                'ligne1' => $newAdresse->getLigne1(),
                'ligne2' => $newAdresse->getLigne2(),
                'codePostal' => $newAdresse->getCodePostal(),
                'ville' => $newAdresse->getVille(),
                'pays' => $newAdresse->getPays(),
            ]);

            if ($adresseExiste) {
                $adresse = $adresseExiste;
            } else {
                $adresse = $newAdresse;
            }
            // Sauvegarder l'adresse dans la base de données
            $userAdresse = new UserAdresse();
            $userAdresse->setAdresse($adresse);
            $userAdresse->setCreatedAt(new DateTimeImmutable());
            $userAdresse->setUpdatedAt(new DateTimeImmutable());
            $user->addUserAdress($userAdresse);
            // $adresse->addUserAdress($adresse);
            $entityManager->persist($userAdresse);
            $entityManager->persist($user);
            $entityManager->flush();

            // Rediriger vers la page des adresses de l'utilisateur après ajout
            return $this->redirectToRoute('app_user_adresses', ['uuid' => $currentUser->getUuid()]);
        }
        // Lier l'adresse à l'utilisateur actuel
        return $this->render('user_adresse/add_adresse.html.twig', [
            'form' => $form->createView(), // Utiliser createView() pour générer le formulaire
        ]);
    }


    #[Route('/user/{uuid}/adresses/{id}/delete', name: 'app_user_adresses_delete', methods: ['POST'])]
    public function removeAdresse(
        string $uuid,
        #[MapEntity(mapping: ['id' => 'id'])] Adresse $adresse,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // $adresse = $entityManager->getRepository(Adresse::class)->find(['id' => $adresse->getId()]);
        if (empty($this->getUser())) {

            return $this->redirectToRoute("app_main");
        }
        $currentUser = $this->getUser();
        if (!$adresse) {
            throw $this->createNotFoundException('Adresse non trouvée');
        }
        $fetchUserAdresse = $adresse->getUserAdresses();
        $userAdresses = [];
        foreach ($fetchUserAdresse as $userAdresse) {
            $userAdresses = $userAdresse;
            $currentUser->removeUserAdress($userAdresse);
            $adresse->removeUserAdress($userAdresse);
            $entityManager->remove($userAdresse);
            $entityManager->remove($adresse);
            $entityManager->flush();
        }
        // dd();
        // dd($userAdresse);

        // $entityManager->remove($userAdresse);
        // $entityManager->flush();
        // $usersByAdresse = [];
        // foreach ($adresse->getUserAdresses() as $userAdresse) {
        //     if ($userAdresse->getUser() === $this->getUser()) {
        //         $usersByAdresse = $userAdresse->getUser();
        //     }
        // }
        // dd($usersByAdresse);
        // $adressesOfUser = [];

        // dd($userAdresse->getUser());
        // dd($adresse)
        // $entityManager->remove($usersByAdresse);
        // dd($adresse->getUserAdresses());
        // $entityManager->remove($adresse);
        // $entityManager->flush();

        // // Vérifie que l'adresse appartient bien à l'utilisateur
        // if ($adresse->getUsers()->getId() !== $id) {
        //     throw $this->createAccessDeniedException('Vous n\'avez pas la permission de supprimer cette adresse');
        // }

        if ($request->isMethod('POST')) {
            foreach ($adresse->getUserAdresses() as $userAdresse) {
                if ($userAdresse->getUser() === $this->getUser()) {
                    $usersByAdresse = $userAdresse->getUser();
                }
            }
            $userLinkedToTheAdresse = $adresse->getUserAdresses();
            $usersForThisAdresse = [];
            foreach ($userLinkedToTheAdresse as $oneUser) {

                $usersForThisAdresse = $oneUser;
            }

            // if ($usersForThisAdresse->getUser() === $this->getUser()) {
            // $userAdresse->User(null);

            // $usersForThisAdresse->getAdresse();
            // $removedThisUser = $usersForThisAdresse->getUser();
            // dump($usersForThisAdresse->getAdresse());
            // dd($adresse);
            // $usersForThisAdresse->remove($adresse);
            // $userAdresse = new UserAdresse();
            // $userAdresse->getAdresse();
            // $adresse->removeUserAdress($usersForThisAdresse);
            // $entityManager->remove($userAdresse);
            // $removedThisUser->removeUserAdress($usersForThisAdresse);
            // $entityManager->persist($usersForThisAdresse);
            // dd("Supprimé adresse du user");
            // $entityManager->flush();
        }


        // $userAdresse = new UserAdresse();
        // $userAdresse->setAdresse($adresse);
        // $user->addUserAdress($userAdresse);
        // // $adresse->addUserAdress($adresse);
        // $entityManager->persist($userAdresse);
        // $entityManager->persist($user);
        // $entityManager->flush();

        // Supprimer l'adresse si la méthode est POST
        // // dd($adresse);
        // dd($usersByAdresse);
        // dd($user);
        // // $usersByAdresse->removeUserAdress($this->getUser());
        // foreach ($adresse as $adresseUser) {
        //     # code...
        //     // $this->getUser()->removeAdresse($adresse);
        //     // $entityManager->flush();
        //     $entityManager->flush();
        // }

        // Redirection vers la liste des adresses de l'utilisateur après suppression
        //     return $this->redirectToRoute('app_user_adresses', ['uuid' => $uuid]);
        // }

        // Si la méthode est GET (normalement, tu ne devrais pas avoir à gérer la suppression via GET, mais par sécurité)
        // Tu peux rediriger ou afficher une page d'erreur
        return $this->redirectToRoute('app_user_adresses', ['uuid' => $uuid]);
    }

    #[Route('/user/{uuid}/adresses/{id}/update', name: 'app_user_adresses_update', methods: ['GET', 'POST'])]
    public function updateAdresse(
        #[MapEntity(mapping: ['uuid' => 'uuid'])] User $user,
        string $uuid,
        string $id,
        Request $request,
        AdresseRepository $adresseRepository,
        EntityManagerInterface $entityManager
    ): Response {
        if (empty($this->getUser()) || $user->getUuid() !== $this->getUser()->getUuid()) {

            return $this->redirectToRoute("app_main");
        }
        $currentUser = $this->getUser();
        $uuidFromUser = Uuid::fromString($user->getUuid());
        $uuidFromUrl = Uuid::fromString($uuid);
        // if (!$uuidFromUser->equals($uuidFromUrl)) {
        //     throw $this->createAccessDeniedException('Vous n\'avez pas la permission de modifier cette adresse pour cet utilisateur');
        // }
        $oldAdresse = $entityManager->getRepository(Adresse::class)->find($id);


        if (!$oldAdresse) {
            // throw $this->createNotFoundException('Adresse non trouvée.'); // arrete le programme
            return $this->redirectToRoute('app_user_adresses', ['uuid' => $user->getUuid(), 'id' => $id]);
        } else {
            $autresUtilisateursParAdresse = $oldAdresse->getUserAdresses();
            // dd($autresUtilisateurs);
            $autresUtilisateurs = [];
            foreach ($autresUtilisateursParAdresse as $oneUser) {

                $autresUtilisateurs[] = $oneUser->getUser();
            }
            // dd($autresUtilisateurs);
            if (count($autresUtilisateurs) > 1) {
                // Si d'autres utilisateur il faut add une nouvelle adresse pas la modifier.
                $newAdresse = new Adresse();
                $newAdresse->setLigne1($oldAdresse->getLigne1());
                $newAdresse->setLigne2($oldAdresse->getLigne2());
                $newAdresse->setCodePostal($oldAdresse->getCodePostal());
                $newAdresse->setVille($oldAdresse->getVille());
                $newAdresse->setPays($oldAdresse->getPays());
                $newAdresse->setUpdatedAt(new DateTimeImmutable());

                // $oldAdresse->removeUser($currentUser)
                // $currentUser->removeAdresse($oldAdresse);
                $form = $this->createForm(AdresseType::class, $newAdresse);
            } else {

                // personne n'a la même adresse il est possible de la modifier.
                $form = $this->createForm(AdresseType::class, $oldAdresse);
            }
            $form->handleRequest($request);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            if (count($autresUtilisateurs) > 1) {
                $adresseExisteDeja = $entityManager->getRepository(Adresse::class)->findOneBy([
                    'ligne1' => $newAdresse->getLigne1(),
                    'ligne2' => $newAdresse->getLigne2(),
                    'codePostal' => $newAdresse->getCodePostal(),
                    'ville' => $newAdresse->getVille(),
                    'pays' => $newAdresse->getPays(),
                ]);
                // dd($adresseExisteDeja);
                if ($adresseExisteDeja) {
                    $newAdresse = $adresseExisteDeja;
                } else {
                    $entityManager->persist($newAdresse);
                }
                $newAdresse->setUpdatedAt(new DateTimeImmutable());

                $user->addAdresse($newAdresse);
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_user_adresses', ['uuid' => $user->getUuid()]);
        }

        return $this->render('user_adresse/update_adresse.html.twig', [
            'user' => $this->getUser(),
            'adresse' => $oldAdresse,
            'form' => $form->createView(),
        ]);
    }
    // /**
    //  * @Route("/admin/user/print", name="admin_user_print")
    //  */
    // #[Route('/admin/user/print', name: 'admin_user_print', methods: ['GET', 'POST'])]
    // public function print(): Response
    // {
    //     return $this->render('admin_user_print.html.twig');
    // }
    // // #[Route('/user/{uuid:user}/adresses/{id:adresse}', name: 'app_one_adresse', methods: ['GET', 'POST'])]
    // // public function showOne(
    // //     User $user,
    // //     Adresse $adresse
    // // ): Response {
    // //     // dd($user);
    // //     return $this->render('user_adresse/show_one.html.twig', [
    // //         'user' => $user,
    // //         'adresse' => $adresse,
    // //     ]);
    // // }

    #[Route('/user/{uuid:user}/adresses/{id:adresse}/default', name: 'app_adress_default', methods: ['GET', 'POST'])]
    public function adresseByDefault(
        User $user,
        Adresse $adresse,
        UserAdresseRepository $userAdresseRepository,
        EntityManagerInterface $entityManager
    ): Response {
        if (empty($this->getUser()) || $user->getUuid() !== $this->getUser()->getUuid()) {

            return $this->redirectToRoute("app_main");
        }

        $userAdresse = $userAdresseRepository->findBy([
            'user' => $user,
            'adresse' => $adresse
        ]);
        // if ($userAdresse) {

        $usersAdresses = $user->getUserAdresses();
        $userAdresses = [];
        foreach ($usersAdresses as $userAdresse) {
            $userAdresses[] = $userAdresse;
            if ($userAdresse->isDefault()) {

                $userAdresse->setIsDefault(false);
                $userAdresse->setUpdatedAt(new DateTimeImmutable());
                $entityManager->persist($userAdresse);
                // dd($userAdresse);
                // Mets tout à faux.
                // dd($userAdresses);
                // $adressesByUser = [];
                // foreach ($userAdresses as $userAdresse) {
                //     $adressesByUser[] = $userAdresse->getAdresse();
                //     if ($userAdresse->isDefault()) {
                //         $userAdresse->setIsDefault(true);
                //         $entityManager->persist($userAdresse);
                //         $entityManager->flush();
                //     }
                // }
                $entityManager->flush();

                // dd($userAdresse);
                // dd($userAdresse);
            }
            // $entityManager->flush();
        }
        foreach ($userAdresses as $theAdresse) {
            if ($theAdresse->getAdresse() === $adresse) {
                $valueIsDefault = $theAdresse->isDefault();
                // dd($valueIsDefault);
                $theAdresse->setIsDefault(true);
                $theAdresse->setUpdatedAt(new DateTimeImmutable());
                $entityManager->persist($theAdresse);
                $entityManager->flush();
                return $this->redirectToRoute('app_user_show', ['uuid' => $user->getUuid()]);
            }
        }
        // }

        // $adrezs = [];
        // foreach ($userAdresse as $adress) {
        //     $adrezs = $adress->isDefault();
        // }
        // // dd($adrezs);
        // if ($adrezs) {
        //     $adress->setIsDefault(!$adrezs);
        //     $entityManager->persist($adress);
        //     $entityManager->flush();
        // }
        return $this->redirectToRoute('app_main');
        // return $this->redirectToRoute('app_user_show', ['uuid' => $user->getUuid()]);


        return $this->render('user_adresse/adresse_default.html.twig', [
            'user' => $user,
            'adresse' => $adresse,
        ]);
    }
}
