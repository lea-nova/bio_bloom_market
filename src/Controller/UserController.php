<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Adresse;


use App\Form\ResetPasswordFormType;
use App\Form\UserType;
use App\Form\AdresseType;
use App\Repository\AdresseRepository;
use App\Repository\UserRepository;
use App\Service\PasswordService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ArgumentResolver\EntityValueResolver;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
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

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\SecurityBundle\SecurityBundle;


use function PHPUnit\Framework\isNull;

class UserController extends AbstractController
{



    #[Route('admin/user/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('admin/user/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(
        UserPasswordHasherInterface $userPasswordHasher,
        Request $request,
        EntityManagerInterface $entityManager,
        PasswordService $passwordService,
        Security $security
    ): Response {

        // dd($request);
        $user = new User();
        // $user->setEmail($request->request->get('email'));
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        // dd($form->getData());
        if ($security->isGranted("ROLE_ADMIN")) {
            $passwordGenerated = $passwordService->generatePassword();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );



            if ($form->get('plainPassword')->getData() !== $form->get("checkPassword")->getData()) {
                return $this->redirectToRoute('app_register');
            }
            $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'passwordGenerated' => $passwordGenerated
        ]);
    }

    #[Route('user/{uuid}', name: 'app_user_show', methods: ['GET'])]
    public function show(
        #[MapEntity(mapping: ['uuid' => 'uuid'], message: "L'utilisateur n'a pas été trouvé")] User $user,
        Security $security
    ): Response {
        $currentUser = $this->getUser();
        if ($user->getUuid() !== $currentUser->getUuid() && !($security->isGranted("ROLE_ADMIN"))) {
            return $this->redirectToRoute('app_user_show', ["uuid" => $currentUser->getUuid()]);
            // return $this->redirectToRoute('app_user_show', ["uuid" => $uuid]);
        }
        return $this->render('user/show.html.twig', [
            'user' => $currentUser,
            'adresses' => $user->getAdresses(),
        ]);
    }

    #[Route('user/{uuid}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity(mapping: ['uuid' => "uuid"])] User $user, EntityManagerInterface $entityManager, Security $security): Response
    {
        $currentUser = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($user->getId() !== $currentUser->getId() &&  !($security->isGranted("ROLE_SUPER_ADMIN"))) {
            return $this->redirectToRoute('app_user_edit', ["uuid" => $currentUser->getUuid()]);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('user/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, #[MapEntity(id: "id")] User $user, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage, SessionInterface $session): Response
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
    public function editPassword(Request $request, #[MapEntity(uuid: "uuid")] User $user, EntityManagerInterface $entityManager, Security $security, HasherUserPasswordHasherInterface $userPasswordHasher): Response
    {
        $currentUser = $this->getUser();
        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);

        // si l'id de l'user connecté n'est pas le même que l'id de l'user de la page.
        if ($user->getId() !== $currentUser->getUuid()) {
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
    public function showAdresses(
        UserRepository $userRepository,
        #[MapEntity(mapping: ['uuid' => 'uuid'])] User $user,
    ): Response {
        $currentUser = $this->getUser();

        $adresses = $user->getAdresses();

        return $this->render('user_adresse/show_adresses.html.twig', [
            'user' => $currentUser,
            'adresses' => $adresses,
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


    #[Route('/user/{uuid}/adresses/{id}/delete', name: 'app_user_adresses_delete', methods: ['POST'])]
    public function removeAdresse(string $uuid, #[MapEntity(id: "id")] Adresse $adresse, Request $request,  EntityManagerInterface $entityManager): Response
    {
        $adresse = $entityManager->getRepository(Adresse::class)->find(['id' => $adresse->getId()]);
        // dd($adresse);
        // dd($adresse);
        if (!$adresse) {
            throw $this->createNotFoundException('Adresse non trouvée');
        }
        dump($adresse);
        $entityManager->remove($adresse);
        $entityManager->flush();

        // // Vérifie que l'adresse appartient bien à l'utilisateur
        // if ($adresse->getUsers()->getId() !== $id) {
        //     throw $this->createAccessDeniedException('Vous n\'avez pas la permission de supprimer cette adresse');
        // }

        if ($request->isMethod('POST')) {
            // Supprimer l'adresse si la méthode est POST
            $entityManager->remove($adresse);
            foreach ($adresse as $adresseUser) {
                # code...
                // $this->getUser()->removeAdresse($adresse);
                // $entityManager->flush();
                $entityManager->flush();
            }

            // Redirection vers la liste des adresses de l'utilisateur après suppression
            return $this->redirectToRoute('app_user_adresses', ['uuid' => $uuid]);
        }

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
    /**
     * @Route("/admin/user/print", name="admin_user_print")
     */
    #[Route('/admin/user/print', name: 'admin_user_print', methods: ['GET', 'POST'])]
    public function print(): Response
    {
        return $this->render('admin_user_print.html.twig');
    }
}
