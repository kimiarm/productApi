<?php

declare(strict_types=1);

namespace App\Domain\Validation;

use Exception;

class ProductValidator
{
    public static function validate(array $data): void
    {
        if (empty($data['name']) || !is_string($data['name'])) {
            throw new Exception("Invalid or missing product name", 400);
        }

        if (!isset($data['price']) && !is_numeric($data['price']) && $data['price'] < 0) {
            throw new Exception("Invalid or missing product price", 400);
    }

        if (empty($data['category']) || !is_string($data['category'])) {
            throw new Exception("Invalid or missing category", 400);
        }

        if (isset($data['attributes']) && !is_array($data['attributes'])) {
            throw new Exception("Attributes must be an array", 400);
        }
    }
}