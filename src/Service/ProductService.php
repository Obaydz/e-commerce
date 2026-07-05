<?php
namespace App\Service;

use App\Entity\Key;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Interface\ProductServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository,
        private EntityManagerInterface $em
    ) {}

    public function getAll(Key $apiKey): array
    {
        return $this->productRepository->findByApiKey($apiKey);
    }

    public function getOne(int $id, Key $apiKey): Product
    {
        $product = $this->productRepository->find($id);

        if (!$product || $product->getCategory()->getApikey() !== $apiKey) {
            throw new \Exception('Product not found', 404);
        }

        return $product;
    }

    public function create(array $data, Key $apiKey): Product
    {
        $category = $this->categoryRepository->find($data['categoryId']);

        if (!$category || $category->getApikey() !== $apiKey) {
            throw new \Exception('Category not found', 404);
        }

        $product = new Product();
        $product->setTitle($data['name']);
        $product->setDescription($data['description'] ?? null);
        $product->setPrice($data['price']);
        $product->setCategory($category);

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

    public function update(int $id, array $data, Key $apiKey): Product
    {
        $product = $this->productRepository->find($id);

        if (!$product || $product->getCategory()->getApikey() !== $apiKey) {
            throw new \Exception('Product not found', 404);
        }

        if (isset($data['name']))        $product->setTitle($data['name']);
        if (isset($data['description'])) $product->setDescription($data['description']);
        if (isset($data['price']))       $product->setPrice($data['price']);

        if (isset($data['categoryId'])) {
            $category = $this->categoryRepository->find($data['categoryId']);

            if (!$category || $category->getApikey() !== $apiKey) {
                throw new \Exception('Category not found', 404);
            }

            $product->setCategory($category);
        }

        $this->em->flush();

        return $product;
    }

    public function delete(int $id, Key $apiKey): void
    {
        $product = $this->productRepository->find($id);

        if (!$product || $product->getCategory()->getApikey() !== $apiKey) {
            throw new \Exception('Product not found', 404);
        }

        $this->em->remove($product);
        $this->em->flush();
    }
}