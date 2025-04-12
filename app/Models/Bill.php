<?php
namespace App\Models;

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

    public function getStatus($id_order){
        $stmt = $this->queryBuilder->select('status,id_order')->from('orders')->where('id_order = :id_order')
        ->setParameter('id_order',$id_order);
        return $stmt->fetchAssociative();
    }

    public function edit($id_order){
        $stmt = $this->queryBuilder->update('orders')
        ->where('id_order = :id_order')
        ->set('status',':status')
        ->setParameters([
            'status'=>$_POST['status'],
            'id_order'=>$id_order
        ]);
        $this->connection->executeStatement($stmt->getSQL(),$stmt->getParameters());
    }

    public function getOrderDetails($id_order) {
        $query = $this->queryBuilder
            ->select('pro_id, quantity')
            ->from('order_details')
            ->where('id_order_detail = :id_order_detail')
            ->setParameter('id_order_detail', $id_order);
    
        return $this->connection->fetchAllAssociative(
            $query->getSQL(),
            $query->getParameters()
        );
    }
    
}
?>