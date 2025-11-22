<?php

use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Src\Controllers\CartController;
use Src\Controllers\LoginController;
use Src\Controllers\NoteController;
use Src\Controllers\OrderController;
use Src\Controllers\ProductController;
use Src\Controllers\RegisterController;

require __DIR__ . '/vendor/autoload.php';

$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();

session_start();

$container->set(PhpRenderer::class, function () use ($container){
    return new PhpRenderer(__DIR__ . '/templates');
});

ORM::configure('mysql:host=database;dbname=docker;charset=utf8mb4');
ORM::configure('username', 'root');
ORM::configure('password', 'tiger');

$app->get('/register', [RegisterController::class, 'index']);
$app->post('/register', [RegisterController::class, 'register']);
$app->get('/login', [LoginController::class, 'index']);
$app->post('/login', [LoginController::class, 'login']);

$app->group('/admin', function () use ($app) {
    $app->get('/notes', [NoteController::class, 'index']);
    $app->get('/logout', [ProductController::class, 'logout']);
});

$app->get('/', [ProductController::class, 'index']);
$app->get('/cart', [ProductController::class, 'index']);
$app->get('/product/cartItems', [CartController::class, 'index']);
$app->post('/cart/add', [CartController::class, 'add']);
$app->post('/cart/minus', [CartController::class, 'minus']);
$app->get('/product/{id}', [ProductController::class, 'show']);
$app->get('/orders', [OrderController::class, 'index']);
$app->post('/orders', [OrderController::class, 'store']);


$app->run();
