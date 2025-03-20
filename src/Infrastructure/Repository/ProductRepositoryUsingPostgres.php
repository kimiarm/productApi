<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Model\Product;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Infrastructure\Database\Database;
use PDO;

class ProductRepositoryUsingPostgres implements ProductRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getPostgresConnection();
    }

    public function create(Product $product): string
    {
        $stmt = $this->db->prepare("INSERT INTO products (id, name, price, category, attributes, created_at) VALUES (:id, :name, :price, :category, :attributes, :created_at)");
        $stmt->execute([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'category' => $product->category,
            'attributes' => json_encode($product->attributes),
            'created_at' => $product->createdAt
        ]);
        return $product->id;
    }

    public function find(string $id): ?Product
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Product(
            $data['name'],
            (float) $data['price'],
            $data['category'],
            json_decode($data['attributes'], true),
            $data['created_at']
        ) : null;
    }

    public function update(string $id, array $data): bool
    {
        $updates = [];
        $params = ['id' => $id];

        foreach ($data as $key => $value) {
            if ($key === 'attributes') {
                $value = json_encode($value);
            }
            $updates[] = "$key = :$key";
            $params[$key] = $value;
        }

        $stmt = $this->db->prepare("UPDATE products SET " . implode(', ', $updates) . " WHERE id = :id");
        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }

    public function delete(string $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function list(array $filters = []): array
    {
        $query = "SELECT * FROM products WHERE 1=1";
        $params = [];

        if (isset($filters['category'])) {
            $query .= " AND category = :category";
            $params['category'] = $filters['category'];
        }

        if (isset($filters['min_price']) && isset($filters['max_price'])) {
            $query .= " AND price BETWEEN :min_price AND :max_price";
            $params['min_price'] = (float) $filters['min_price'];
            $params['max_price'] = (float) $filters['max_price'];
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn ($p) => new Product(
            $p['name'],
            (float) $p['price'],
            $p['category'],
            json_decode($p['attributes'], true),
            $p['created_at']
        ), $products);
    }
}