<?php
namespace App\Controllers;
use App\Common\Blade;
use App\Models\Product;
use Rakit\Validation\Validator;

class ProductController {
    protected $proModel;
    public function __construct(){
        $this->proModel = new Product(); 
    }

    public function index(){
        $products = $this->proModel->index();
        Blade::render('products.index',['products'=>$products]);
    }

    public function create(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $validator = new Validator();
            $validation = $validator->make([
                'product_name' => $_POST['product_name'],
                'product_img' => $_POST['product_img'],
                'price' => $_POST['price'],
                'quantity' => $_POST['quantity'],
                'category_id' => $_POST['category_id'],
            ],[
                'product_name' => 'required|max:255',
                'product_img' => 'uploaded_file:0,2MB,JPG,PNG',
                'price' => 'required|integer',
                'quantity' => 'required|integer',
                'category_id' => 'required|boolean'
            ]);
            $validation->validate();
            if($validation->fails()){
                $_SESSION['errors'] = $validation->errors()->toArray();
                $_SESSION['old'] = $_POST;
                header('location: ' . $_ENV['BASE_URL'] . 'admin/products/create');
                exit;
            }
            $imageLink = null;
            $image = $_FILES['product_img'];
            if($_FILES['product_img']['name'] != ""){
                
                $imageName = time() . '_' . $image['name'];
                move_uploaded_file($image['tmp_name'],"uploads/products/$imageName");
                $imageLink = "uploads/products/$imageName";
            }
            $this->proModel->create($imageLink);
            $_SESSION['message'] = "Thêm thành công";
            header('location: ' . $_ENV['BASE_URL'] . 'admin/products');
            exit;
        }
        $categories = $this->proModel->getCate();
        Blade::render('products.create',[
            'categories'=>$categories,
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? []
        ]);
        unset($_SESSION['errors'], $_SESSION['old']);
    }

    public function update($id){
        $categories = $this->proModel->getCate();
        $proOld = $this->proModel->getProOld($id);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $validator = new Validator();
            $validation = $validator->make([
                'product_name' => $_POST['product_name'],
                'product_img' => $_POST['product_img'],
                'price' => $_POST['price'],
                'quantity' => $_POST['quantity'],
                'category_id' => $_POST['category_id'],
            ],[
                'product_name' => 'required|max:255',
                'product_img' => 'uploaded_file:0,2MB,JPG,PNG',
                'price' => 'required|integer',
                'quantity' => 'required|integer',
                'category_id' => 'required'
            ]);
            $validation->validate();
            if($validation->fails()){
                $_SESSION['errors'] = $validation->errors()->toArray();
                header('location: ' . $_ENV['BASE_URL'] . 'admin/products/create');
                exit;
            }
            $imageLink = $proOld['product_img'];
            $image = $_FILES['product_img'];
            if($image['name'] != "")  {
                unlink($imageLink);
                $imageName = time() . '_' . $image['name'];
                move_uploaded_file($image['tmp_name'],"uploads/products/$imageName");
                $imageLink = "uploads/products/$imageName";
            }
            $this->proModel->update($id,$imageLink);
            $_SESSION['message'] = "Sửa thành công";
            header('location: ' . $_ENV['BASE_URL'] . 'admin/products');
            exit;
        }
        Blade::render('products.update',[
            'proOld'=>$proOld,
            'categories'=>$categories,
            'errors' => $_SESSION['errors'] ?? [],
        ]);
        unset($_SESSION['errors']);
    }

    public function destroy($id){
        $this->proModel->destroy($id);
        $_SESSION['message'] = "Xóa thành công";
        header('location: ' . $_ENV['BASE_URL'] . 'admin/products');
        exit;
    }

    public function show($id){
        $detailPro = $this->proModel->getProOld($id);
        Blade::render('products.show',['detailPro'=>$detailPro]);
    }
}
?>