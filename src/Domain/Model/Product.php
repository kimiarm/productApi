<?php

declare(strict_types=1);

namespace App\Domain\Model;

use Ramsey\Uuid\Uuid;

class Product
{
    public readonly string $id;

    public function __construct(
        public string $name,
        public float $price,
        public string $category,
        public array $attributes = [],
        public string $createdAt = ''
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = $createdAt ?: date('c');
    }
}
