<?php
// src/Interface/CategoryServiceInterface.php

namespace App\Interface;

use App\Entity\Key;

interface CategoryServiceInterface
{
    public function getAll(Key $apiKey): array;
    public function create(string $name, Key $apiKey): \App\Entity\Category;
    public function update(int $id, string $name, Key $apiKey): \App\Entity\Category;
    public function delete(int $id, Key $apiKey): void;
}