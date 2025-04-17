<?php
namespace App\Models;

use Exception;

class Bill extends Model {
    public function index(){
        $stmt = $this->queryBuilder->select('*')->from('orders')->orderBy('id_order','desc');
        return $stmt->fetchAllAssociative();
    }

    public function detail($id_order){
        $stmt = $this->queryBuilder->select('od.*','p.product_name,p.product_img,p.price','o.*')->from('order_details','od')
        ->innerJoin('od','orders','o','od.order_id = o.id_order')
        ->innerJoin('od','products','p','od.pro_id = p.id_product')
        ->where('order_id = :order_id')
        ->setParameter('order_id',$id_order);
        return $stmt->fetchAllAssociative();
    }

    // public function getStatus($id_order){
    //     $stmt = $this->queryBuilder->select('status,id_order')->from('orders')->where('id_order = :id_order')
    //     ->setParameter('id_order',$id_order);
    //     return $stmt->fetchAssociative();
    // }

    // public function edit($id_order){
    //     $stmt = $this->queryBuilder->update('orders')
    //     ->where('id_order = :id_order')
    //     ->set('status',':status')
    //     ->setParameters([
    //         'status'=>$_POST['status'],
    //         'id_order'=>$id_order
    //     ]);
    //     $this->connection->executeStatement($stmt->getSQL(),$stmt->getParameters());
    // }

    public function getStatus($id_order) {
        $query = $this->queryBuilder
            ->select('status,id_order')
            ->from('orders')
            ->where('id_order = :id_order')
            ->setParameter('id_order', $id_order);
        
        return $query->fetchAssociative();
    }

    public function edit($id_order) {
        $stmt = $this->queryBuilder->update('orders')
            ->where('id_order = :id_order')
            ->set('status', ':status')
            ->setParameters([
                'status' => $_POST['status'],
                'id_order' => $id_order
            ]);
        $this->connection->executeStatement($stmt->getSQL(), $stmt->getParameters());
    }

    private function getOrderDetails($id_order) {
        $query = $this->queryBuilder
            ->select('pro_id, quantity')
            ->from('order_details')
            ->where('order_id = :order_id')
            ->setParameter('order_id', $id_order);
        
        return $this->connection->fetchAllAssociative($query->getSQL(), $query->getParameters());   
    }

    private function removeDuplicateOrderDetails($order_id) {
        $query = $this->queryBuilder
            ->select('id_order_detail, pro_id, quantity')
            ->from('order_details')
            ->where('order_id = :order_id')
            ->setParameter('order_id', $order_id);
        
        $records = $this->connection->fetchAllAssociative($query->getSQL(), $query->getParameters());

        if (empty($records)) {
            return;
        }

        $grouped = [];

        foreach ($records as $record) {
            $pro_id = $record['pro_id'];
            $id_order_detail = $record['id_order_detail'];
            $quantity = (int) $record['quantity'];
        
            if (!isset($grouped[$pro_id])) {
                $grouped[$pro_id] = [
                    'total_quantity' => $quantity,
                    'keep_id' => $id_order_detail,
                    'delete_ids' => []
                ];
            } else {
                $grouped[$pro_id]['total_quantity'] += $quantity;
                $grouped[$pro_id]['delete_ids'][] = $id_order_detail;
            }
        }

        foreach ($grouped as $pro_id => $data) {
            if (!empty($data['delete_ids'])) {
                $this->connection->update('order_details', 
                    ['quantity' => $data['total_quantity']], 
                    ['order_id' => $data['keep_id']]
                );  

                $placeholders = implode(',', array_fill(0, count($data['delete_ids']), '?'));
                $sql = "DELETE FROM order_details WHERE order_id IN ($placeholders)";
                $this->connection->executeStatement($sql, $data['delete_ids']);
            }
        }
    }

    public function handleStockOnDeliveryStatusChange($id_order, $oldStatus, $newStatus, $cartModel) {
        // Chỉ xử lý nếu trạng thái cũ không phải "delivering" và trạng thái mới là "delivering"
        if ($oldStatus != "delivering" && $newStatus == "delivering") {
            // Xóa trùng lặp trong order_details
            $this->removeDuplicateOrderDetails($id_order);

            // Lấy chi tiết đơn hàng
            // $orderDetails = $this->getOrderDetails($id_order);
            $orderDetails = $this->getOrderDetails($id_order);

            if (empty($orderDetails)) {
                throw new Exception("Không tìm thấy chi tiết đơn hàng cho id_order = $id_order");
            }

            // Trừ số lượng cho từng sản phẩm
            foreach ($orderDetails as $item) {
                if (!isset($item['quantity']) || $item['quantity'] <= 0) {
                    throw new Exception("Số lượng không hợp lệ cho sản phẩm pro_id = " . $item['pro_id']);
                }

                // Trừ số lượng trong bảng products
                $cartModel->reduceStock($item['pro_id'], $item['quantity']);
            }
        }
    }

   
    
}
?>