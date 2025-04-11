<?php
namespace App\Controllers;
use App\Common\Blade;
use App\Models\Cart;
use App\Models\Category; 

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
        $totalPrice = $this->cartModel->totalPrice($id);
        $proCart = $this->cartModel->showCart($id); 
        Blade::render('client.cart',[
            'categories'=>$categories,
            'proCart'=>$proCart,
            'totalPrice'=>$totalPrice
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

    public function updateCartQuantityPro(){
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
                } if ($newQuantity == 0){
                    echo "Lỗi";
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
                $this->updateCartQuantityPro();
            }elseif (isset($_POST['btn_deletecart'])) {
                $this->deleteCart();
            } else if(isset($_POST['btn_checkout'])){
                $this->checkoutPro();
                
            }
        }
    } 
    
    
    //order
    function checkoutPro()
    {
        // if (!isset($_POST['btn_checkout']) || !isset($_SESSION['id_user'])) {
        //     return;
        // }

        if(isset($_POST['btn_checkout'])){
            $id_user = $_SESSION['id_user'];
            $info = $this->cartModel->getAllInfoUser($id_user);
            $selectedPro = $_POST['selected_pro'] ?? [];

            if (empty($selectedPro)) {
                echo "Vui lòng chọn sản phẩm để thanh toán";
                return;
            }

            $productsSelect = $this->cartModel->getSelectedPro($id_user, $selectedPro);

            $totalCheckout = 0;
            foreach ($productsSelect as $product) {
                $totalCheckout += $product['price'] * $product['quantity'];
            }

            $categories = $this->cartModel->getCategories();
            Blade::render('client.checkout', [
                'productsSelect' => $productsSelect,
                'totalCheckout' => $totalCheckout,
                'categories' => $categories,
                'info' => $info,
            ]);
        }
        
    }


    function placeOrder()
{
    if (!isset($_SESSION['id_user']) || !isset($_POST['btn_placeorder'])) {
        return;
    }
    if(isset($_SESSION['id_user']) && isset($_POST['btn_placeorder'])){
        $id_user = $_SESSION['id_user'];
        $selectedPro = $_POST['selected_pro'] ?? [];


        $id_orders = $this->cartModel->createOrders($id_user);
        $cartItems = $this->cartModel->getCartByIdUser($id_user);

        foreach ($cartItems as $item) {
            $pro_id = (int)$item['product_id'];

            // Nếu sản phẩm không nằm trong danh sách được chọn -> bỏ qua
            if (!isset($selectedPro[$pro_id])) {
                continue;
            }

            $quantity = $selectedPro[$pro_id];
            $product_price = $item['price'];

            $this->cartModel->addOrdersDetail($id_orders, $pro_id, $product_price, $quantity);
            // $this->cartModel->reduceStock($pro_id, $quantity);
            $this->cartModel->clearCart($id_user, $pro_id);
        }

        header("Location: " . $_ENV['BASE_URL'] . 'home' );
        exit;
    }
}

}
?>