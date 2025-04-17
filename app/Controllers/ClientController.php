<?php

namespace App\Controllers;

use App\Common\Blade;
use App\Models\Client;

class ClientController
{
    protected $client;
    public function __construct()
    {
        $this->client = new Client();
    }

    public function index()
    {
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


    public function detail($id)
    {
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
        Blade::render('client.detail', [
            'detail' => $detail,
            'proForCate' => $proForCate,
            'categories' => $categories
        ]);
    }
    public function info($id_user)
    {
        $user = $this->client->getUser($id_user);
        $categories = $this->client->getCategories();
        $filteredCategories = [];
        $seenCategories = [];
        foreach ($categories as $category) {
            if (isset($category['id_category'], $category['category_name']) && !in_array($category['id_category'], $seenCategories)) {
                $filteredCategories[] = [
                    'id_category' => $category['id_category'],
                    'category_name' => $category['category_name']
                ];
                $seenCategories[] = $category['id_category'];
            }
        }
        $categories = $filteredCategories;
        $orders = $this->client->getBill($id_user);
        $filteredOrders = [];
        $seenOrders = [];
        foreach ($orders as $order) {
            if (isset($order['id_order']) && !in_array($order['id_order'], $seenOrders)) {
                $filteredOrders[] = $order;
                $seenOrders[] = $order['id_order'];
            }
        }
        $orders = $filteredOrders;

        if (isset($_POST['btn_changeinfo'])) {
            $newPhone = $_POST['phone'] ?? null;
            $newAddress = $_POST['address'] ?? null;
            $newPassword = $_POST['password'] ?? null;
            $newImage = null;

            // Xử lý upload ảnh
            if ($_FILES['user_img']['name']) {
                $tmp = $_FILES['user_img']['tmp_name'];
                $img = $_FILES['user_img']['name'];
                move_uploaded_file($tmp, 'uploads/users/' . $img);
                $newImage = 'uploads/users/' . $img;
            } else {
                $newImage = $user['user_img'];
            }

            if ($id_user && ($newPhone || $newAddress || $newPassword || $newImage)) {
                $this->client->updateUserInfo($id_user, $newPhone, $newAddress, $newPassword, $newImage);
                $_SESSION['success'] = "Thông tin tài khoản đã được cập nhật thành công!";
            } else {
                $_SESSION['error'] = "Vui lòng nhập ít nhất một thông tin để cập nhật!";
            }

            header("Location: " . $_ENV['BASE_URL'] . "info/$id_user");
            exit;
        }

        $orderDetail = null;
        $orderItems = [];

        if (isset($_POST['btn_view'])) {
            $id_order = $_POST['id_order'] ?? null;
            if ($id_order) {
                $orderDetail = $this->client->find($id_order);
                if ($orderDetail) {
                    $orderItems = $this->client->getItems($id_order);
                    $filteredOrderItems = [];
                    $seenOrderItems = [];
                    foreach ($orderItems as $item) {
                        if (isset($item['id_order_detail']) && !in_array($item['id_order_detail'], $seenOrderItems)) {
                            $filteredOrderItems[] = $item;
                            $seenOrderItems[] = $item['id_order_detail'];
                        }
                    }
                    $orderItems = $filteredOrderItems;
                }
            }
        }
        if (isset($_POST['btn_cancel'])) {
            $id_order = $_POST['id_order'] ?? null;
            if ($id_order) {
                $this->client->cancelorder($id_order);
                $_SESSION['success'] = "Đã hủy đơn hàng thành công!";
                header("Location: " . $_ENV['BASE_URL'] . "info/$id_user");
                exit;
            }
        }
        Blade::render('client.info', [
            'categories' => $categories,
            'orders' => $orders,
            'user' => $user,
            'orderDetail' => $orderDetail,
            'orderItems' => $orderItems
        ]);
    }
    public function bill()
    {
        $categories = $this->client->getCategories();
        Blade::render('client.bill', [
            'categories' => $categories
        ]);
    }
}
