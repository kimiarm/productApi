<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\Product;

interface ProductRepositoryInterface
{
    public function create(Product $product): string;
    public function find(string $id): ?Product;
    public function update(string $id, array $data): bool;
    public function delete(string $id): bool;
    public function list(array $filters = []): array;
}
