<?php

use App\Controllers\CategoryController;
session_start();
include 'vendor/autoload.php';
use App\Controllers\HomeController;
use Bramus\Router\Router;
use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
const ROOT_PATH = __DIR__;
$router = new Router();
$router->get('/',HomeController::class . '@index');
// $router->before('GET|POST', '/admin(|/.*)', function() {
//     AuthMiddleware::checkAdmin();
// });

// $router->before('GET|POST', '/', function() {
//     AuthMiddleware::log();
// });
// $router->match('GET|POST','/login',LoginController::class . '@login');
// $router->post('/logout',LoginController::class . '@logout');
$router->mount('/admin', function() use ($router){
    $router->get('/', HomeController::class . '@index');
//     $router->mount('/user', function() use ($router){
//         $router->get('/',UserController::class . '@getAllUser');
//         $router->match('GET|POST','/add', UserController::class . '@addUser');
//         $router->match('GET|POST', '/delete/(\d+)',UserController::class . '@deleteUser');
//         $router->match('GET|POST', '/update/(\d+)',UserController::class . '@updateUser');
//     });

    $router->mount('/categories',function() use ($router){
        $router->get('/',CategoryController::class . '@index');
        $router->match('GET|POST','/create', CategoryController::class . '@create');
        $router->match('GET|POST', '/destroy/(\d+)',CategoryController::class . '@destroy');
        $router->match('GET|POST', '/update/(\d+)',CategoryController::class . '@update');
    });

//     $router->mount('/product', function() use ($router){
//         $router->get('/',ProductController::class . '@getAllPro');
//         $router->match('GET|POST','/add', ProductController::class . '@addPro');
//         $router->match('GET|POST', '/delete/(\d+)',ProductController::class . '@deletePro');
//         $router->match('GET|POST', '/update/(\d+)',ProductController::class . '@updatePro');
//     });
});

$router->run();
?>