<?php
// src/Interface/ProductServiceInterface.php

namespace App\Interface;

use App\Entity\Key;

interface ProductServiceInterface
{
    public function getAll(Key $apiKey): array;
    public function getOne(int $id, Key $apiKey): \App\Entity\Product;
    public function create(array $data, Key $apiKey): \App\Entity\Product;
    public function update(int $id, array $data, Key $apiKey): \App\Entity\Product;
    public function delete(int $id, Key $apiKey): void;
}