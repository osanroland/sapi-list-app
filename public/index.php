<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../template');
$twig = new \Twig\Environment($loader);

$client = new App\Client\SapiClient($_ENV['API_USER'], $_ENV['API_PASSWORD']);
$service = new App\Service\EmailListService($client);
$controller = new App\Controller\ListController($service, $twig);

$router = new App\Router\Router();
$router->dispatch(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $controller);
