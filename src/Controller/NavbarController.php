<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Service\CategorieService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NavbarController extends AbstractController
{
    // #[Route('/navbar', name: 'app_navbar')]
    public function navbar(CategorieService $categorieService, CategorieRepository $categorieRepository): Response
    {

        if ($this->isGranted('ROLE_ADMIN')) {
            $categories = $categorieService->getAllCategories();
        } else {
            $categories = $categorieService->getAllCategoriesVisible();
        }


        return $this->render('_partials\_navbar.html.twig', [
            "categories" => $categories,
        ]);
    }
}
