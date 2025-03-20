<?php

declare(strict_types=1);

namespace App\Application\Controller;

use App\Application\Service\ProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Exception;

class ProductController
{
    private ProductService $productService;

    public function __construct(ProductService $service)
    {
        $this->productService = $service;
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $id = $this->productService->createProduct($data);
            return new JsonResponse(['id' => $id], JsonResponse::HTTP_CREATED);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode() ?: JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    public function find(string $id): JsonResponse
    {
        try {
            $product = $this->productService->getProduct($id);
            return $product ? new JsonResponse($product) : new JsonResponse(null, JsonResponse::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    public function update(string $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            return $this->productService->updateProduct($id, $data)
                ? new JsonResponse(['message' => 'Updated'])
                : new JsonResponse(null, JsonResponse::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    public function delete(string $id): JsonResponse
    {
        return $this->productService->deleteProduct($id)
            ? new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT)
            : new JsonResponse(null, JsonResponse::HTTP_NOT_FOUND);
    }

    public function list(Request $request): JsonResponse
    {
        try {
            $filters = $request->query->all();
            return new JsonResponse($this->productService->listProducts($filters));
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}