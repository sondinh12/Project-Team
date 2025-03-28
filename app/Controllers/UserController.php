<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Common\Blade;
use App\Common\Database;
use Rakit\Validation\Validator;
use Rakit\Validation\Rule;
use Doctrine\DBAL\Connection;

class UniqueRule extends Rule
{
    protected $message = "The :attribute has already been taken.";
    protected Connection $db;
    protected string $table;
    protected string $column;

    public function __construct(Connection $db, string $table, string $column)
    {
        $this->db = $db;
        $this->table = $table;
        $this->column = $column;
    }

    public function check($value): bool
    {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE {$this->column} = ?";
        $count = $this->db->fetchOne($query, [$value]);

        return $count == 0;
    }
}



class UserController
{
    protected $UserModel;
    public function __construct()
    {
        $this->UserModel = new UserModel();
    }
    public function index()
    {
        $users = $this->UserModel->index();
        return Blade::render('users.index', compact('users'));
    }
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new Validator();

            $db = Database::getConnection();
            $validator->addValidator('unique', new UniqueRule($db, 'users', 'user_name'));

            $validation = $validator->make($_POST, [
                'name' => 'required|max:255|alpha_spaces',
                'user_name' => 'required|max:255|alpha_num|unique',
                'email' => 'required|email',
                'phone' => 'required|numeric|min:10',
                'password' => 'required|min:6',
                'role' => 'required|in:admin,user',
                'status' => 'required|in:active,inactive',
                'user_img' => 'uploaded_file:0,2M,png,jpg,jpeg'
            ]);
            if ($_FILES['user_img']['name']) {
                $tmp = $_FILES['user_img']['tmp_name'];
                $img = $_FILES['user_img']['name'];
                move_uploaded_file($tmp, 'uploads/users/' . $img);
                $imageName = 'uploads/users/' . $img;
            } else {
                $imageName = '';
            }
            $validation->validate();

            if ($validation->fails()) {
                $_SESSION['errors'] = $validation->errors()->toArray();
                $_SESSION['old'] = $_POST;
                header('location: ' . $_ENV['BASE_URL'] . 'admin/users/create');
                exit;
            }

            $this->UserModel->create($imageName);
            $_SESSION['message'] = "Thêm tài khoản thành công";
            header('location:' . $_ENV['BASE_URL'] . 'admin/users');
            exit;
        }

        Blade::render('users.create', [
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? []
        ]);
        unset($_SESSION['errors'], $_SESSION['old']);
    }
    public function update($id)
    {
        $user = $this->UserModel->FindUserByID($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new Validator();
            $validation = $validator->make($_POST, [
                'name' => 'required|max:255|alpha_spaces',
                'email' => 'required|email',
                'phone' => 'required|numeric|min:10',
                'password' => 'nullable|min:6',
                'role' => 'required|in:admin,user',
                'status' => 'required|in:active,inactive',
                'user_img' => 'uploaded_file:0,2M,png,jpg,jpeg|nullable'
            ]);

            if ($_FILES['user_img']['name']) {
                $tmp = $_FILES['user_img']['tmp_name'];
                $img = $_FILES['user_img']['name'];
                move_uploaded_file($tmp, 'uploads/users/' . $img);
                $imageName = 'uploads/users/' . $img;
            } else {
                $imageName = $user['user_img'];
            }
            $validation->validate();
            if ($validation->fails()) {
                $_SESSION['errors'] = $validation->errors()->toArray();
                $_SESSION['old'] = $_POST;
                header('location: ' . $_ENV['BASE_URL'] . 'admin/users/update/' . $id);
                exit;
            }
            $this->UserModel->update($id, $imageName);
            $_SESSION['message'] = "Cập nhật tài khoản thành công";
            header('location: ' . $_ENV['BASE_URL'] . 'admin/users');
        }
        Blade::render('users.update', [
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? [],
            'user' => $user
        ]);
        unset($_SESSION['errors'], $_SESSION['old']);
    }
    // public function destroy($id)
    // {
    //     $this->UserModel->destroy($id);
    //     $_SESSION['message'] = "Xóa tài khoản thành công";
    //     header('location: ' . $_ENV['BASE_URL'] . 'admin/users');
    //     exit;
    // }
    public function changestatus($id)
    {
        $user = $this->UserModel->FindUserByID($id);
        if ($user['status'] == 'active') {
            $this->UserModel->changeStatus($id, 'inactive');
        } else {
            $this->UserModel->changeStatus($id, 'active');
        }
        header('location: ' . $_ENV['BASE_URL'] . 'admin/users');
        exit;
    }
}
