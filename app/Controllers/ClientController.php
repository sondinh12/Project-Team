<?php
namespace App\Controllers;
use App\Common\Blade;
use App\Models\Client;

class ClientController {
    protected $client;
    public function __construct(){
        $this->client = new Client();
    }

    public function index(){
        $products = $this->client->index();
        $categories = $this->client->getCategories(); 
        if (!is_array($categories) || empty($categories)) {
            var_dump('getCategories() trả về rỗng:', $categories);
        }
        
        $filteredCategories = [];
        $seen = [];
        foreach ($categories as $category) {
            if (isset($category['id_category'], $category['category_name']) && !in_array($category['id_category'], $seen)) {
                $filteredCategories[] = [
                    'id_category' => $category['id_category'],
                    'category_name' => $category['category_name']
                ];
                $seen[] = $category['id_category'];
            }
        }
        $categories = $filteredCategories;  
        Blade::render('client.index',[
            'products'=>$products,
            'categories'=>$categories
        ]);
    }


    public function detail($id){
        $detail = $this->client->detail($id);
        $categories = $this->client->getCategories();   
        if ($detail) {
            $categoryId = $detail['category_id'];
            $proForCate = $this->client->getProductforCate($categoryId,$id);
            $filteredProForCate = [];
        $seen = [];
        foreach ($proForCate as $product) {
            if (!in_array($product['id_product'], $seen)) {
                $filteredProForCate[] = $product;
                $seen[] = $product['id_product'];
            }
        }
        $proForCate = $filteredProForCate;
        } else {
            $proForCate = [];
        }
        Blade::render('client.detail',[
            'detail'=>$detail,
            'proForCate'=>$proForCate,
            'categories'=>$categories
        ]);
    }

    public function login(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $user = $this->client->login();
            if ($user) {
                // Lưu thông tin người dùng vào session
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['id_user'] = $user['id_user']; // hoặc $user['id_user'] tùy cột DB
                
    
                // Chuyển hướng sau đăng nhập
                header('Location: ' . $_ENV['BASE_URL'] . 'home');
                exit;
            } else {
                // Thông báo lỗi đăng nhập
                Blade::render('client.login', ['error' => 'Sai tài khoản hoặc mật khẩu']);
            }
    
        }
        Blade::render('client.login');
    }

    public function bill(){
        $categories = $this->client->getCategories();  
        Blade::render('client.bill',[
            'categories'=>$categories
        ]);
    }

}
?>