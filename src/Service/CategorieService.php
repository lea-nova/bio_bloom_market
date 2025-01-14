<?php

namespace App\Service;

use App\Repository\CategorieRepository;

class CategorieService
{
    private $categorieRepository;

    public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }

    public function getAllCategories(): array
    {
        return $this->categorieRepository->findAll();
    }
    public function getAllCategoriesVisible(): array
    {
        return $this->categorieRepository->findBy(['visible' => true]);
    }
}
