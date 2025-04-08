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
use App\Controllers\CartController;
use App\Controllers\ClientController;


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


$router->mount('', function() use ($router){
    $router->get('/',function(){
        header("Location: " . $_ENV['BASE_URL'] . 'home');
        exit();
    });
    $router->get('/home',ClientController::class . '@index');
    $router->get('/detail/(\d+)',ClientController::class . '@detail');
    $router->get('/cart',CartController::class . '@cart');
    $router->post('/cart/addToCart',CartController::class . '@addtoCart');
    $router->post('/cart/updateToCart',CartController::class . '@updateCartQuantity');
    $router->post('/cart/deleteToCart',CartController::class . '@deleteCart');
    $router->post('/handleaction',CartController::class . '@handleAction');
    $router->match('GET|POST','/login',ClientController::class . '@login');
});

$router->run();
