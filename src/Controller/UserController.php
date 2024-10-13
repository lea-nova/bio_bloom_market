<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Adresse;


use App\Form\ResetPasswordFormType;
use App\Form\UserType;
use App\Form\AdresseType;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as HasherUserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Uid\Uuid;


use function PHPUnit\Framework\isNull;

class UserController extends AbstractController
{

    // private $csrfTokenManager;
    // CsrfTokenManagerInterface $csrfTokenManager
    public function __construct()
    {
        // $this->csrfTokenManager = $csrfTokenManager;
    }

    #[Route('admin/user/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('admin/user/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('user/{uuid}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, Security $security): Response
    {
        $currentUser = $this->getUser();

        if ($user->getId() !== $currentUser->getId() && !($security->isGranted("ROLE_ADMIN"))) {
            return $this->redirectToRoute('app_user_show', ["uuid" => $currentUser->getUuid()]);
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'adresses' => $user->getAdresses(),

        ]);
    }

    #[Route('user/{uuid}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, Security $security): Response
    {
        $currentUser = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($user->getId() !== $currentUser->getId() &&  !($security->isGranted("ROLE_SUPER_ADMIN"))) {
            return $this->redirectToRoute('app_user_edit', ["uuid" => $currentUser->getUuid()]);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('user/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage, SessionInterface $session): Response
    {

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            // Vérifiez si l'utilisateur à supprimer est l'utilisateur actuellement connecté
            $currentUser = $tokenStorage->getToken()->getUser();

            // Supprimez l'utilisateur
            $entityManager->remove($user);
            $entityManager->flush();

            // Si l'utilisateur actuellement connecté est supprimé, déconnectez-le
            if ($currentUser === $user) {
                $tokenStorage->setToken(null);
                $session->invalidate();
                $currentUser = null;
                return $this->redirectToRoute('app_logout'); // Assurez-vous que 'app_logout' est une route qui gère la déconnexion
            }
        }
        return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('user/{uuid}/edit/password', name: 'app_user_edit_password', methods: ['GET', 'POST'])]
    public function editPassword(Request $request, User $user, EntityManagerInterface $entityManager, Security $security, HasherUserPasswordHasherInterface $userPasswordHasher): Response
    {
        $currentUser = $this->getUser();
        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);

        // si l'id de l'user connecté n'est pas le même que l'id de l'user de la page.
        if ($user->getId() !== $currentUser->getId()) {
            return $this->redirectToRoute('app_main');
        }


        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->get("oldPassword")->getData()); // message écrit en dur qui doit être le mdp de base.
            // $checkOldPasswordHashed =  $userPasswordHasher->hashPassword($user, $form->get('oldPassword')->getData());
            // dd($checkOldPasswordHashed); // mdp hashé 
            // dd($user->getPassword()); // dans bdd mdp haché 
            if ($userPasswordHasher->isPasswordValid($user, $form->get("oldPassword")->getData())) {
                if ($form->get('newPassword')->getData() !== $form->get("checkNewPassword")->getData()) {
                    return $this->redirectToRoute('app_main');
                }
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('newPassword')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_user_show', ["uuid" => $currentUser->getUuid()], Response::HTTP_SEE_OTHER);
            } else {
                dd('Pas valide');
            }
        };
        // dd($currentUser); // user connecté
        // dd($user); // user qu'on veut edit.



        // return $this->redirectToRoute('app_user_edit_password', ["id" => $currentUser->getId()], Response::HTTP_SEE_OTHER);



        return $this->render('user/_change_password_form.html.twig', [
            'user' => $user,
            'editPassword' => $form->createView(),
        ]);
    }

    #[Route('user/{uuid}/adresses', name: 'app_user_adresses', methods: ['GET'])]
    public function showAdresses(): Response
    {
        $currentUser = $this->getUser();
        $adresses = $currentUser->getAdresses();
        return $this->render('user_adresse/show_adresses.html.twig', [
            'user' => $currentUser,
            'adresses' => $adresses,
        ]);
    }

    #[Route('user/{uuid}/adresses/new', name: 'app_user_adresses_new', methods: ['GET', 'POST'])]
    public function addAdresse(Request $request, EntityManagerInterface $entityManager): Response
    {
        $currentUser = $this->getUser();

        if (!$currentUser) {
            throw $this->createNotFoundException('Utilisateur non connecté');
        }


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
            $adresse->addUser($currentUser);
            $entityManager->persist($adresse);
            $entityManager->flush();

            // Rediriger vers la page des adresses de l'utilisateur après ajout
            return $this->redirectToRoute('app_user_adresses', ['uuid' => $currentUser->getUuid()]);
        }
        // Lier l'adresse à l'utilisateur actuel
        return $this->render('user_adresse/add_adresse.html.twig', [
            'form' => $form->createView(), // Utiliser createView() pour générer le formulaire
        ]);
    }


    #[Route('/user/{uuid}/adresses/{ulid_adresse}/delete', name: 'app_user_adresses_delete', methods: ['POST'])]
    public function removeAdresse(string $uuid, string $ulid_adresse, Request $request,  EntityManagerInterface $entityManager): Response
    {

        $adresse = $entityManager->getRepository(Adresse::class)->findBy(['ulid' => $ulid_adresse]);
        // dd($adresse);
        if (!$adresse) {
            throw $this->createNotFoundException('Adresse non trouvée');
        }

        // // Vérifie que l'adresse appartient bien à l'utilisateur
        // if ($adresse->getUsers()->getId() !== $id) {
        //     throw $this->createAccessDeniedException('Vous n\'avez pas la permission de supprimer cette adresse');
        // }

        if ($request->isMethod('POST')) {
            // Supprimer l'adresse si la méthode est POST
            // $entityManager->removeAdresse($adresse);

            foreach ($adresse as $adresseUser) {
                # code...
                $adresseUser->removeUser($this->getUser());
                $entityManager->flush();
            }

            // Redirection vers la liste des adresses de l'utilisateur après suppression
            return $this->redirectToRoute('app_user_adresses', ['uuid' => $uuid]);
        }

        // Si la méthode est GET (normalement, tu ne devrais pas avoir à gérer la suppression via GET, mais par sécurité)
        // Tu peux rediriger ou afficher une page d'erreur
        return $this->redirectToRoute('app_user_adresses', ['uuid' => $uuid]);
    }

    #[Route('/user/{uuid}/adresses/{ulid_adresse}/update', name: 'app_user_adresses_update', methods: ['GET', 'POST'])]
    public function updateAdresse(User $user, string $uuid, string $ulid_adresse, Request $request, EntityManagerInterface $entityManager): Response
    {
        $currentUser = $this->getUser();

        $uuidFromUser = Uuid::fromString($user->getUuid());
        $uuidFromUrl = Uuid::fromString($uuid);

        if (!$uuidFromUser->equals($uuidFromUrl)) {
            throw $this->createAccessDeniedException('Vous n\'avez pas la permission de modifier cette adresse pour cet utilisateur');
        }
        $oldAdresse = $entityManager->getRepository(Adresse::class)->find($ulid_adresse);
        if (!$oldAdresse) {
            // throw $this->createNotFoundException('Adresse non trouvée.'); // arrete le programme
            return $this->redirectToRoute('app_user_adresses', ['uuid' => $user->getUuid()]);
        } else {
            $autresUtilisateurs = $oldAdresse->getUsers();
            if (count($autresUtilisateurs) > 1) {
                // Si d'autres utilisateur il faut add une nouvelle adresse pas la modifier.
                $newAdresse = new Adresse();
                $newAdresse->setLigne1($oldAdresse->getLigne1());
                $newAdresse->setLigne2($oldAdresse->getLigne2());
                $newAdresse->setCodePostal($oldAdresse->getCodePostal());
                $newAdresse->setVille($oldAdresse->getVille());
                $newAdresse->setPays($oldAdresse->getPays());

                // $oldAdresse->removeUser($currentUser)
                $currentUser->removeAdresse($oldAdresse);
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

                if ($adresseExisteDeja) {
                    $newAdresse = $adresseExisteDeja;
                } else {
                    $entityManager->persist($newAdresse);
                }
                $user->addAdresse($newAdresse);
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_user_adresses', ['uuid' => $user->getUuid()]);
        }

        return $this->render('user_adresse/update_adresse.html.twig', [
            'user' => $user,
            // 'adresse' => $adresse,
            'form' => $form->createView(),
        ]);
    }
}
