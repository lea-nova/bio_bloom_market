<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {


        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/admin', name: 'admin_app_main',  methods: ['GET'])]
    public function dashboard(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) { // Redirige vers la route de la page d'accueil 
            return $this->redirectToRoute('app_main');
        }
        return $this->render('admin/admin_home.html.twig');
    }
    // return $this->redirectToRoute('app_main', []);
}
