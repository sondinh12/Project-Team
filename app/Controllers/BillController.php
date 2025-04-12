<?php
namespace App\Controllers;
use App\Common\Blade;
use App\Models\Bill;
use App\Models\Cart;
class BillController {
    protected $billModel;
    public function __construct() {
        $this->billModel = new Bill();
    }   

    public function index(){
        $orders = $this->billModel->index();
        $perPage = 10; // Số đơn hàng mỗi trang
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $totalOrders = count($orders);
    $totalPages = ceil($totalOrders / $perPage);

    $start = ($currentPage - 1) * $perPage;
    $ordersPaginated = array_slice($orders, $start, $perPage);
        Blade::render('admin.bill.index',[
            'orders'=>$ordersPaginated,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage
        ]);
    }

    public function detail($id_order){
        $details = $this->billModel->detail($id_order);
        Blade::render('admin.bill.detail',[
            'details'=>$details
        ]);
    }

    // public function edit($id_order){
    //     $oldStatus = $this->billModel->getStatus($id_order);
    //     if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //         $this->billModel->edit($id_order);
    //         header('location:' . $_ENV['BASE_URL'] . '/admin/bill');
    //         exit;
    //     }
    //     Blade::render('admin.bill.edit',[
    //         'oldStatus'=>$oldStatus
    //     ]);
    // }

    public function edit($id_order) {
        $oldStatus = $this->billModel->getStatus($id_order); // Lấy trạng thái cũ
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newStatus = $_POST['status'];
    
            // Nếu chuyển sang "Đang giao" mà trước đó không phải "Đang giao"
            if ($oldStatus != "delivering" && $newStatus == "delivering") {
                // Lấy danh sách sản phẩm trong đơn hàng
                $orderDetails = $this->billModel->getOrderDetails($id_order); // Bạn cần thêm hàm này
                echo '<pre>';
                // Gọi model Cart để trừ số lượng
                $cartModel = new Cart();
                foreach ($orderDetails as $item) {
                    $cartModel->reduceStock($item['pro_id'], $item['quantity']);
                }
            }
    
            // Cập nhật trạng thái
            $this->billModel->edit($id_order);
    
            header('location:' . $_ENV['BASE_URL'] . '/admin/bill');
            exit;
        }
    
        Blade::render('admin.bill.edit', [
            'oldStatus' => $oldStatus,
        ]);
    }


    
    
}
?>