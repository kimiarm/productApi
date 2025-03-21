<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RouteCollection;
use App\Application\Controller\ProductController;
use App\Application\Service\ProductService;
use App\Infrastructure\Repository\ProductRepositoryUsingMongoDB;
use App\Infrastructure\Repository\ProductRepositoryUsingPostgres;
use Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\JsonResponse;

require_once __DIR__. '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__. '/../');
$dotenv->load();

$repository = $_ENV['DB_TYPE'] === 'mongo'
    ? new ProductRepositoryUsingMongoDB()
    : new ProductRepositoryUsingPostgres();

$productService = new ProductService($repository);
$controller = new ProductController($productService);

/** @var RouteCollection $routes */
$routes = require __DIR__. '/../src/Application/Routes/api.php';

$request = Request::createFromGlobals();
$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

try {
    $parameters = $matcher->match($request->getPathInfo());
    $controllerMethod = $parameters['_controller'];
    unset($parameters['_controller'], $parameters['_route']);
    $response = call_user_func_array($controllerMethod, array_merge([$request], $parameters));
    $response->send();
} catch (Exception $e) {
    (new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND))->send();
}