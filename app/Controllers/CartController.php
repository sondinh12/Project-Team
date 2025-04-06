<?php
namespace App\Controllers;
use App\Common\Blade;
use App\Models\Cart; 

class CartController {
    protected $cartModel;
    public function __construct() {
        $this->cartModel = new Cart();
    }
    
    public function cart(){
        if(!isset($_SESSION['id_user'])){
            header('location: ' . $_ENV['BASE_URL'] . 'login');
        }
        $id = $_SESSION['id_user'];
        // var_dump($id);
        $categories = $this->cartModel->getCategories();
        $proCart = $this->cartModel->showCart($id); 
        Blade::render('client.cart',[
            'categories'=>$categories,
            'proCart'=>$proCart
        ]);
    }

    public function addToCart(){
        if(!isset($_SESSION['id_user'])){
            header('location: ' . $_ENV['BASE_URL'] . 'login');
        }
        $id_user = $_SESSION['id_user'];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->cartModel->addtoCart($id_user);
            echo "Thêm thành công";
            header('location: ' . $_ENV['BASE_URL'] . 'home');
            exit;
        }
    }

    public function updateCartQuantity(){
        if(isset($_SESSION['id_user'])){
            $id_user = $_SESSION['id_user'];
            if(isset($_POST['btn_updatecart'])){
                $quantityField = 'quantity-'.$_POST['btn_updatecart'];
                $newQuantity = isset($_POST[$quantityField]) ? $_POST[$quantityField] : 1;
                if (empty($newQuantity) || $newQuantity <= 0) {
                    $newQuantity = 1;
                }
                if ($newQuantity > 0) {
                    $this->cartModel->updateCartQuantity($id_user, $newQuantity);
                    header("location:" . $_ENV['BASE_URL'] . 'cart');
                    exit;
                }
            }
        }
    }

    public function deleteCart(){
        if(isset($_SESSION['id_user'])){
            $id_user = $_SESSION['id_user'];
            if(isset($_POST['btn_deletecart'])){
                $this->cartModel->deleteCart($id_user);
                header("location:" . $_ENV['BASE_URL'] . 'cart');
                exit;
            }
        }
    }

    public function handleAction(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(isset($_POST['btn_updatecart'])){
                $this->updateCartQuantity();
            }elseif (isset($_POST['btn_deletecart'])) {
                $this->deleteCart();
            }
        }
    }   
}
?>