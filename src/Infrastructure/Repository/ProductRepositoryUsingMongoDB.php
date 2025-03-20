<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Model\Product;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Infrastructure\Database\Database;
use MongoDB\Collection;

class ProductRepositoryUsingMongoDB implements ProductRepositoryInterface
{
    private Collection $collection;

    public function __construct()
    {
        $this->collection = Database::getMongoConnection()->selectCollection('products');
    }

    public function create(Product $product): string
    {
        $this->collection->insertOne((array) $product);
        return $product->id;
    }

    public function find(string $id): ?Product
    {
        $data = $this->collection->findOne(['id' => $id]);
        return $data ? new Product(...(array) $data) : null;
    }

    public function update(string $id, array $data): bool
    {
        $result = $this->collection->updateOne(['id' => $id], ['$set' => $data]);
        return $result->getModifiedCount() > 0;
    }

    public function delete(string $id): bool
    {
        $result = $this->collection->deleteOne(['id' => $id]);
        return $result->getDeletedCount() > 0;
    }

    public function list(array $filters = []): array
    {
        $query = [];

        if (isset($filters['category'])) {
            $query['category'] = $filters['category'];
        }

        if (isset($filters['min_price']) && isset($filters['max_price'])) {
            $query['price'] = ['$gte' => (float)$filters['min_price'], '$lte' => (float)$filters['max_price']];
        }

        $products = $this->collection->find($query)->toArray();

        return array_map(fn ($p) => new Product(...(array) $p), $products);
    }
}