<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\Model\Product;
use App\Domain\DTO\ProductDTO;
use App\Domain\Validation\ProductValidator;
use Exception;

class ProductService
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createProduct(array $data): string
    {
        ProductValidator::validate($data);
        $product = new Product(
            $data['name'],
            (float) $data['price'],
            $data['category'],
            $data['attributes'] ?? []
        );
        return $this->repository->create($product);
    }

    public function getProduct(string $id): ?array
    {
        $product = $this->repository->find($id);
        return $product ? ProductDTO::fromModel($product) : null;
    }

    public function updateProduct(string $id, array $data): bool
    {
        if (empty($data)) {
            throw new Exception("No update data provided", 400);
        }
        return $this->repository->update($id, $data);
    }

    public function deleteProduct(string $id): bool
    {
        return $this->repository->delete($id);
    }

    public function listProducts(array $filters = []): array
    {
        return array_map(fn($product) => ProductDTO::fromModel($product), $this->repository->list($filters));
    }
}