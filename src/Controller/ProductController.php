<?php

// src/Controller/ProductController.php

namespace App\Controller;

use App\Interface\ProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/products')]
class ProductController extends AbstractController
{
    public function __construct(private ProductServiceInterface $productService) {}

    #[Route('', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $apiKey = $request->attributes->get('apiKey');
        $products = $this->productService->getAll($apiKey);

        return $this->json($products);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id, Request $request): JsonResponse
    {
        $apiKey = $request->attributes->get('apiKey');

        try {
            $product = $this->productService->getOne($id, $apiKey);
            return $this->json($product);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $apiKey = $request->attributes->get('apiKey');
        $data = json_decode($request->getContent(), true);

        if (empty($data['name']) || empty($data['price']) || empty($data['categoryId'])) {
            return $this->json(['error' => 'name, price and categoryId are required'], 422);
        }

        try {
            $product = $this->productService->create($data, $apiKey);
            return $this->json($product, 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $apiKey = $request->attributes->get('apiKey');
        $data = json_decode($request->getContent(), true);

        if (empty($data)) {
            return $this->json(['error' => 'No data provided'], 422);
        }

        try {
            $product = $this->productService->update($id, $data, $apiKey);
            return $this->json($product);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, Request $request): JsonResponse
    {
        $apiKey = $request->attributes->get('apiKey');

        try {
            $this->productService->delete($id, $apiKey);
            return $this->json(['message' => 'Product deleted'], 200);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }
}