<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

use function PHPUnit\Framework\isNull;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user,);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('plainPassword')->getData() !== $form->get("checkPassword")->getData()) {
                return $this->redirectToRoute('app_register');
            }
            // if (isNull($form->get('email')->getData())) {
            //     $message = $this->addFlash('erreur', 'L\'adresse n\'est pas au bon format !');
            //     return $this->redirectToRoute('app_register', [$message]);
            // }
            if (!preg_match('/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i', $form->get('email')->getData())) {
                $this->addFlash('error', 'L\'adresse e-mail n\'est pas valide.');
                return $this->redirectToRoute('app_register');
            }
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }
        $message = $request->query->get('message');
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form
        ]);
    }
}
