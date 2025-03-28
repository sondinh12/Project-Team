<?php
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
include 'vendor/autoload.php';

use App\Controllers\HomeController;
use Bramus\Router\Router;
use Dotenv\Dotenv;
use App\Controllers\CategoryController;
use App\Controllers\ProductController;
use App\Controllers\UserController;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
const ROOT_PATH = __DIR__;
$router = new Router();
$router->get('/', HomeController::class . '@index');
// $router->before('GET|POST', '/admin(|/.*)', function() {
//     AuthMiddleware::checkAdmin();
// });

// $router->before('GET|POST', '/', function() {
//     AuthMiddleware::log();
// });
// $router->match('GET|POST','/login',LoginController::class . '@login');
// $router->post('/logout',LoginController::class . '@logout');
$router->mount('/admin', function () use ($router) {
    $router->get('/', HomeController::class . '@index');
    $router->mount('/users', function () use ($router) {
        $router->get('/', UserController::class . '@index');
        $router->match('GET|POST', '/create', UserController::class . '@create');
        // $router->match('GET|POST', '/destroy/(\d+)', UserController::class . '@destroy');
        $router->match('GET|POST', '/changestatus/(\d+)', UserController::class . '@changestatus');
        $router->match('GET|POST', '/update/(\d+)', UserController::class . '@update');
    });

    $router->mount('/products', function() use ($router){
        $router->get('/',ProductController::class . '@index');
        $router->get('/show/(\d+)',ProductController::class . '@show');
        $router->match('GET|POST','/create', ProductController::class . '@create');
        $router->match('GET|POST', '/destroy/(\d+)',ProductController::class . '@destroy');
        $router->match('GET|POST', '/update/(\d+)',ProductController::class . '@update');
    });
    $router->mount('/categories', function () use ($router) {
        $router->get('/', CategoryController::class . '@index');
        $router->match('GET|POST', '/create', CategoryController::class . '@create');
        $router->match('GET|POST', '/destroy/(\d+)', CategoryController::class . '@destroy');
        $router->match('GET|POST', '/update/(\d+)', CategoryController::class . '@update');
    });

});


$router->run();
