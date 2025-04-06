<?php
namespace App\Models;

use App\Models\Model;

class Cart extends Model {

    public function getCategories(){
        $stmt = $this->queryBuilder->select('*')->from('categories')->orderBy('id_category','DESC');
        return $stmt->fetchAllAssociative();
    }
    
    public function showCart($id){
        $stmt = $this->queryBuilder->select('c.id_cart,c.user_id,c.product_id,c.quantity as quantityCart,p.id_product,p.product_name,p.price,p.product_img,p.quantity')
        ->from('cart','c')->Join('c','products','p','p.id_product = c.product_id')
        ->where('c.user_id = :user_id')->setParameter('user_id',$id);
        return $stmt->fetchAllAssociative();
    }

    public function checkProQuantity(){
        $stmt = $this->queryBuilder->select('quantity')->from('products')->where('id_product = :id_product')
        ->setParameter('id_product',$_POST['product_id']);
        return $stmt->fetchAssociative();
    }

    public function insertProToCart($id_user){
        $stmt = $this->queryBuilder->insert('cart')->values([
            'user_id'=>':user_id',
            'product_id'=>':product_id',
            'quantity'=>':quantity',
            'created_at'=>'NOW()',
            'updated_at'=>'NOW()'
        ])
        ->setParameters([
            'user_id'=>$id_user,
            'product_id'=>$_POST['product_id'],
            'quantity'=>$_POST['quantity'],
        ]);
        $this->connection->executeStatement($stmt->getSQL(),$stmt->getParameters());   
    }

    public function updateCartQuantity($id_user,$newQuantity){
        $stmt = $this->queryBuilder->update('cart')
        ->where('user_id = :user_id ')
        ->andWhere('product_id = :product_id')
        ->set('quantity',':quantity')
        ->set('updated_at','NOW()')
        ->setParameters([
            'user_id'=>$id_user,
            'quantity'=>$newQuantity,   
            'product_id'=>$_POST['btn_updatecart'],
        ]);
        $this->connection->executeStatement($stmt->getSQL(),$stmt->getParameters());  
    }

    public function getCartProduct($id_user){
        $stmt = $this->queryBuilder->select('*')->from('cart')
        ->where('user_id = :user_id AND product_id = :product_id')
        ->setParameters([
            'user_id'=>$id_user,
            'product_id'=>$_POST['product_id']
        ]);
        return $stmt->fetchAssociative();
    }

    public function addtoCart($id_user){
        $stoke = $this->checkProQuantity();
        if($stoke && $stoke['quantity'] < (int) $_POST['quantity']){
            return "Số lượng trong kho không đủ";
        }
        $existingCart = $this->getCartProduct($id_user);
        
        if($existingCart){
            $newQuantity = $existingCart['quantity'] + (int) $_POST['quantity'];
            return $this->updateCartQuantity($id_user,$newQuantity);
        } else{
            return $this->insertProToCart($id_user);
        }
    }

    public function deleteCart($id_user){
        $stmt = $this->queryBuilder->delete('cart')->where('user_id = :user_id')->andWhere('product_id = :product_id')
        ->setParameters([
            'user_id'=>$id_user,
            'product_id'=>$_POST['btn_deletecart']
        ]);
        $this->connection->executeStatement($stmt->getSQL(),$stmt->getParameters());  
        
    }

}
?>