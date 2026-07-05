<?php
// src/Service/CategoryService.php

namespace App\Service;

use App\Entity\Key;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Interface\CategoryServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService implements CategoryServiceInterface
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private EntityManagerInterface $em
    ) {}

    public function getAll(Key $apiKey): array
    {
        return $this->categoryRepository->findByApiKey($apiKey);
    }

    public function create(string $name, Key $apiKey): Category
    {
        $category = new Category();
        $category->setTitle($name);
        $category->setApiKey($apiKey);

        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }

    public function update(int $id, string $name, Key $apiKey): Category
    {
        $category = $this->categoryRepository->find($id);

        if (!$category || $category->getApikey() !== $apiKey) {
            throw new \Exception('Category not found', 404);
        }

        $category->setTitle($name);
        $this->em->flush();

        return $category;
    }

    public function delete(int $id, Key $apiKey): void
    {
        $category = $this->categoryRepository->find($id);

        if (!$category || $category->getApikey() !== $apiKey) {
            throw new \Exception('Category not found', 404);
        }

        $this->em->remove($category);
        $this->em->flush();
    }
}
?>