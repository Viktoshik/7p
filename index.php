<?php

use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Src\Controllers\CartController;
use Src\Controllers\ProductController;

require __DIR__ . '/vendor/autoload.php';

$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();

$container->set(PhpRenderer::class, function () use ($container){
    return new PhpRenderer(__DIR__ . '/templates');
});

ORM::configure('mysql:host=database;dbname=docker;charset=utf8mb4');
ORM::configure('username', 'root');
ORM::configure('password', 'tiger');

$app->get('/', [ProductController::class, 'index']);
$app->post('/cart/add', [CartController::class, 'add']);

$app->run();
