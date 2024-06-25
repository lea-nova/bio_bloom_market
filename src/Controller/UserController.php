<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordFormType;
use App\Form\UserType;
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

    #[Route('user/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, Security $security): Response
    {
        $currentUser = $this->getUser();

        if ($user->getId() !== $currentUser->getId() && !($security->isGranted("ROLE_ADMIN"))) {
            return $this->redirectToRoute('app_user_show', ["id" => $currentUser->getId()]);
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('user/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, Security $security): Response
    {
        $currentUser = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($user->getId() !== $currentUser->getId() &&  !($security->isGranted("ROLE_SUPER_ADMIN"))) {
            return $this->redirectToRoute('app_user_edit', ["id" => $currentUser->getId()]);
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

    #[Route('user/{id}/edit/password', name: 'app_user_edit_password', methods: ['GET', 'POST'])]
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
                return $this->redirectToRoute('app_user_show', ["id" => $currentUser->getId()], Response::HTTP_SEE_OTHER);
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
}
