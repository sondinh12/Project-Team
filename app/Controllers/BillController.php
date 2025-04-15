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

    // public function edit($id_order) {
    //     $oldStatus = $this->billModel->getStatus($id_order); // Lấy trạng thái cũ
    //     var_dump($oldStatus);
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $newStatus = $_POST['status'];
    
    //         // Nếu chuyển sang "Đang giao" mà trước đó không phải "Đang giao"
    //         if ($oldStatus != "delivering" && $newStatus == "delivering") {
    //             // Lấy danh sách sản phẩm trong đơn hàng
    //             $orderDetails = $this->billModel->getOrderDetails($id_order); // Bạn cần thêm hàm này
    //             echo '<pre>';
    //             // Gọi model Cart để trừ số lượng
    //             $cartModel = new Cart();
    //             foreach ($orderDetails as $item) {
    //                 $cartModel->reduceStock($item['pro_id'], $item['quantity']);
    //             }
    //         }
    
    //         // Cập nhật trạng thái
    //         $this->billModel->edit($id_order);
    
    //         header('location:' . $_ENV['BASE_URL'] . '/admin/bill');
    //         exit;
    //     }
    
    //     Blade::render('admin.bill.edit', [
    //         'oldStatus' => $oldStatus,
    //     ]);
    // }

    //fix

    // public function edit($id_order) {
    //     $id_order = (int) $id_order;
    //     $oldStatus = $this->billModel->getStatus($id_order);

    //     // var_dump($id_order); // Lấy trạng thái cũ
    
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $newStatus = $_POST['status'];
    
    //         // Debug trạng thái
    //         // error_log("oldStatus: $oldStatus, newStatus: $newStatus");
    
    //         // Nếu chuyển sang "Đang giao" mà trước đó không phải "Đang giao"
    //         if ($oldStatus != "delivering" && $newStatus == "delivering") {
    //             // Lấy danh sách sản phẩm trong đơn hàng
    //             $this->billModel->removeDuplicateOrderDetails($id_order);
    //             $orderDetails = $this->billModel->getOrderDetails($id_order);
    //             // var_dump($orderDetails);
    //             // var_dump($newStatus);
    //             // Debug orderDetails
    //             // error_log("orderDetails: " . print_r($orderDetails, true));
    
    //             // Kiểm tra nếu $orderDetails rỗng
    //             if (empty($orderDetails)) {
    //                 die("Lỗi: Không tìm thấy chi tiết đơn hàng cho id_order = $id_order");
    //             }
    
    //             // Gọi model Cart để trừ số lượng
    //             $cartModel = new Cart();
    //             foreach ($orderDetails as $item) {
    //                 if (!isset($item['quantity']) || $item['quantity'] <= 0) {
    //                     die("Lỗi: Số lượng không hợp lệ cho sản phẩm pro_id = " . $item['pro_id']);
    //                 }
    //                 $cartModel->reduceStock($item['pro_id'], $item['quantity']);
    //             }
    //         } else {
    //             // error_log("Điều kiện không thỏa mãn: oldStatus=$oldStatus, newStatus=$newStatus");
    //         }
    
    //         // Cập nhật trạng thái
            
    //         $this->billModel->edit($id_order);
    
    //         header('location:' . $_ENV['BASE_URL'] . '/admin/bill');
    //         exit;
    //     }
    
    //     Blade::render('admin.bill.edit', [
    //         'oldStatus' => $oldStatus,
    //     ]);
    // }

    public function edit($id_order) {
        $id_order = (int) $id_order;
        $oldStatus = $this->billModel->getStatus($id_order);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newStatus = $_POST['status'];

            // Gọi hàm mới để xử lý trừ số lượng
            $cartModel = new Cart();
            $this->billModel->handleStockOnDeliveryStatusChange($id_order, $oldStatus, $newStatus, $cartModel);

            // Cập nhật trạng thái đơn hàng
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