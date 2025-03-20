<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use App\Application\Controller\ProductController;
use App\Infrastructure\Repository\ProductRepositoryUsingMongoDB;
use App\Infrastructure\Repository\ProductRepositoryUsingPostgres;
use App\Application\Service\ProductService;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__. '/../../../');
$dotenv->load();

$repository = $_ENV['DB_TYPE'] === 'mongodb'
    ? new ProductRepositoryMongo()
    : new ProductRepositoryPostgres();

$productService = new ProductService($repository);
$controller = new ProductController($productService);

$routes = new RouteCollection();
$routes->add('create_product', new Route('/products', ['_controller' => [$controller, 'create']], [], [], '', [], ['POST']));
$routes->add('get_product', new Route('/products/{id}', ['_controller' => [$controller, 'find']], [], [], '', [], ['GET']));
$routes->add('update_product', new Route('/products/{id}', ['_controller' => [$controller, 'update']], [], [], '', [], ['PATCH']));
$routes->add('delete_product', new Route('/products/{id}', ['_controller' => [$controller, 'delete']], [], [], '', [], ['DELETE']));
$routes->add('list_products', new Route('/products', ['_controller' => [$controller, 'list']], [], [], '', [], ['GET']));

return $routes;