<?php
namespace App\Controllers;
use App\Common\Blade;
use Rakit\Validation\Validator;
use App\Models\Category;

class CategoryController {
    protected $cateModel;
    public function __construct(){
        $this->cateModel = new Category();
    }

    public function index(){
        $categories = $this->cateModel->index();
        Blade::render('categories.index',['categories'=>$categories]);
    }

    public function create(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $validator = new Validator();
            $validation = $validator->make([
                'category_name' => $_POST['category_name'],
            ],[
                'category_name' => 'required|max:255|alpha'
            ]);
            $validation->validate();
            if($validation->fails()){
                $_SESSION['errors'] = $validation->errors()->toArray();
                $_SESSION['old'] = $_POST;
                header('location: ' . $_ENV['BASE_URL'] . 'admin/categories/create');
                exit;
            }
            $this->cateModel->create();
            $_SESSION['message'] = "Thêm mới thành công"; 
            header('location:' . $_ENV['BASE_URL'] . 'admin/categories/');
            exit;
        }
        Blade::render('categories.create',[
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? []
        ]);
        unset($_SESSION['errors'], $_SESSION['old']);
    }

    public function update($id){
        $cateOld = $this->cateModel->cateOld($id);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $validater = new Validator;
            $validation = $validater->make([
                'category_name' => $_POST['category_name'],
            ], [
                'category_name' => 'required|alpha',
            ]);
            $validation->validate();
            if($validation->fails()){
                $_SESSION['errors'] = $validation->errors()->toArray();
                header('location: ' . $_ENV['BASE_URL'] . 'admin/categories/update/' . $id);
                exit;
            }
            $this->cateModel->update($id);
            $_SESSION['message'] = "Sửa thành công"; 
            header('location: ' . $_ENV['BASE_URL'] . 'admin/categories/');
            exit;
        }
        Blade::render('categories.update',[
            'cateOld'=>$cateOld,
            'errors' => $_SESSION['errors'] ?? [],
        ]);
        unset($_SESSION['errors']);
    }

    public function destroy($id){
        $this->cateModel->destroy($id);
        $_SESSION['message'] = "Xóa thành công"; 
        header('location: ' . $_ENV['BASE_URL'] . 'admin/categories/');
        exit;
    }
}
?>