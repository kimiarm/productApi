<?php

declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\Controller\ProductController;
use App\Application\Service\ProductService;
use App\Infrastructure\Repository\ProductRepositoryMongo;

class ProductApiTest extends TestCase
{
    private ProductController $controller;

    protected function setUp(): void
    {
        $repository = new ProductRepositoryMongo();
        $service = new ProductService($repository);
        $this->controller = new ProductController($service);
    }

    public function testCreateProduct(): void
    {
        $request = new Request([], [], [], [], [], [], json_encode([
            'name' => 'Laptop',
            'price' => 1200.50,
            'category' => 'electronics',
            'attributes' => ['brand' => 'Apple']
        ]));

        $response = $this->controller->create($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testGetProduct(): void
    {
        $request = new Request();
        $response = $this->controller->find('valid-product-id');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertContains($response->getStatusCode(), [200, 404]);
    }

    public function testUpdateProduct(): void
    {
        $request = new Request([], [], [], [], [], [], json_encode(['price' => 1300.00]));
        $response = $this->controller->update('valid-product-id', $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertContains($response->getStatusCode(), [200, 404]);
    }

    public function testDeleteProduct(): void
    {
        $response = $this->controller->delete('valid-product-id');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertContains($response->getStatusCode(), [204, 404]);
    }
}