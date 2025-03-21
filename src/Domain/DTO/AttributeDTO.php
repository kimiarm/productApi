<?php

declare(strict_types=1);

namespace App\Domain\DTO;

class AttributeDTO
{
    public function __construct(
        public readonly string $brand,
        public readonly ?string $color = null,
        public readonly ?string $storage = null,
        public readonly ?string $size = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            brand: $data['brand'] ?? '',
            color: $data['color'] ?? null,
            storage: $data['storage'] ?? null,
            size: $data['size'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'brand'   => $this->brand,
            'color'   => $this->color,
            'storage' => $this->storage,
            'size'    => $this->size
        ]);
    }
}