<?php
namespace App\Controllers;
use App\Common\Blade;
use App\Models\Bill;
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

    public function edit($id_order){
        $oldStatus = $this->billModel->getStatus($id_order);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->billModel->edit($id_order);
            header('location:' . $_ENV['BASE_URL'] . '/admin/bill');
            exit;
        }
        Blade::render('admin.bill.edit',[
            'oldStatus'=>$oldStatus
        ]);
    }
}
?>