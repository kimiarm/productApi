<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Application\Service\ProductService;
use App\Domain\Model\Product;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\DTO\ProductDTO;
use Exception;

class ProductServiceTest extends TestCase
{
    private ProductService $service;
    private ProductRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ProductRepositoryInterface::class);
        $this->service = new ProductService($this->repository);
    }

    public function testCreateProduct(): void
    {
        $data = [
            'name' => 'Laptop',
            'price' => 1200.50,
            'category' => 'electronics',
            'attributes' => ['brand' => 'Apple']
        ];

        $this->repository->expects($this->once())
            ->method('create')
            ->willReturn('123-uuid');

        $result = $this->service->createProduct($data);

        $this->assertEquals('123-uuid', $result);
    }

    public function testGetProduct(): void
    {
        $product = new Product('Laptop', 1200.50, 'electronics', ['brand' => 'Apple']);

        $this->repository->expects($this->once())
            ->method('find')
            ->willReturn($product);

        $result = $this->service->getProduct($product->id);

        $this->assertEquals(ProductDTO::fromModel($product), $result);
    }

    public function testUpdateProduct(): void
    {
        $this->repository->expects($this->once())
            ->method('update')
            ->willReturn(true);

        $result = $this->service->updateProduct('123-uuid', ['price' => 1300.00]);

        $this->assertTrue($result);
    }

    public function testDeleteProduct(): void
    {
        $this->repository->expects($this->once())
            ->method('delete')
            ->willReturn(true);

        $result = $this->service->deleteProduct('123-uuid');

        $this->assertTrue($result);
    }
}