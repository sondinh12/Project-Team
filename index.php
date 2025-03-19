<?php
session_start();
include 'vendor/autoload.php';
use App\Controllers\ProductController;
use App\Controllers\UserController;
use App\Controllers\HomeController;
use App\Controllers\CategoryController;
use App\Middlewares\AuthMiddleware;
use Bramus\Router\Router;
use App\Controllers\LoginController;
use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
const ROOT_PATH = __DIR__;
$router = new Router();
$router->before('GET|POST', '/admin(|/.*)', function() {
    AuthMiddleware::checkAdmin();
});

$router->before('GET|POST', '/', function() {
    AuthMiddleware::log();
});
$router->match('GET|POST','/login',LoginController::class . '@login');
$router->post('/logout',LoginController::class . '@logout');
$router->mount('/admin', function() use ($router){
    // AuthMiddleware::checkAdmin();    
    $router->get('/', HomeController::class . '@home');
    $router->mount('/user', function() use ($router){
        $router->get('/',UserController::class . '@getAllUser');
        $router->match('GET|POST','/add', UserController::class . '@addUser');
        $router->match('GET|POST', '/delete/(\d+)',UserController::class . '@deleteUser');
        $router->match('GET|POST', '/update/(\d+)',UserController::class . '@updateUser');
    });

    $router->mount('/category',function() use ($router){
        $router->get('/',CategoryController::class . '@getAllCate');
        $router->match('GET|POST','/add', CategoryController::class . '@addCate');
        $router->match('GET|POST', '/delete/(\d+)',CategoryController::class . '@deleteCate');
        $router->match('GET|POST', '/update/(\d+)',CategoryController::class . '@updateCate');
    });

    $router->mount('/product', function() use ($router){
        $router->get('/',ProductController::class . '@getAllPro');
        $router->match('GET|POST','/add', ProductController::class . '@addPro');
        $router->match('GET|POST', '/delete/(\d+)',ProductController::class . '@deletePro');
        $router->match('GET|POST', '/update/(\d+)',ProductController::class . '@updatePro');
    });
});

$router->run();
?>