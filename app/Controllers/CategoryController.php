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
            $this->cateModel->create();
            $_SESSION['message'] = "Thêm mới thành công"; 
            header('location:' . $_ENV['BASE_URL'] . 'admin/categories/');
            exit;
        }
        Blade::render('categories.create');
    }

    public function update($id){
        $cateOld = $this->cateModel->cateOld($id);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->cateModel->update($id);
            $_SESSION['message'] = "Sửa thành công"; 
            header('location: ' . $_ENV['BASE_URL'] . 'admin/categories/');
            exit;
        }
        Blade::render('categories.update',['cateOld'=>$cateOld]);
    }

    public function destroy($id){
        $this->cateModel->destroy($id);
        $_SESSION['message'] = "Xóa thành công"; 
        header('location: ' . $_ENV['BASE_URL'] . 'admin/categories/');
        exit;
    }
}
?>