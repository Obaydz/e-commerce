<?php
// src/Controller/CategoryController.php

namespace App\Controller;

use App\Interface\CategoryServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/categories')]
class CategoryController extends AbstractController
{
    public function __construct(private CategoryServiceInterface $categoryService) {}

    #[Route('', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $apiKey = $request->attributes->get('apiKey');
        $categories = $this->categoryService->getAll($apiKey);

        return $this->json($categories);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $apiKey = $request->attributes->get('apiKey');
        $data = json_decode($request->getContent(), true);

        $category = $this->categoryService->create($data['name'], $apiKey);

        return $this->json($category, 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $apiKey = $request->attributes->get('apiKey');
        $data = json_decode($request->getContent(), true);

        $category = $this->categoryService->update($id, $data['name'], $apiKey);

        return $this->json($category);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, Request $request): JsonResponse
    {
        $apiKey = $request->attributes->get('apiKey');
        $this->categoryService->delete($id, $apiKey);

        return $this->json(['message' => 'Deleted'], 200);
    }
}