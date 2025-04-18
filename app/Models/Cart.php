<?php
namespace App\Models;

use App\Models\Model;
use Exception;

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

    public function totalPrice($id_user)
    {
        $stmt = $this->queryBuilder
            ->select('SUM(prod.price * ca.quantity) AS total_price')
            ->from('cart', 'ca')
            ->join('ca', 'products', 'prod', 'ca.product_id = prod.id_product')
            ->where('ca.user_id = :user_id')
            ->setParameter('user_id', $id_user);
    
        $result = $this->connection->fetchAssociative($stmt->getSQL(), $stmt->getParameters());
    
        return $result['total_price'] ?? 0;
    }

    //order

    function getAllInfoUser($id_user) {
        $stmt = $this->queryBuilder
            ->select('*')
            ->from('users')
            ->where('id_user = :id_user')
            ->setParameter('id_user', $id_user);
    
        return $this->connection->fetchAssociative(
            $stmt->getSQL(),
            $stmt->getParameters()
        );
    }
    
    function getSelectedPro($id_user, $product_id) {
        $productPlaceholders = [];
        $params = ['user_id' => $id_user];
    
        foreach ($product_id as $index => $id) {
            $key = "id_$index";
            $productPlaceholders[] = ":$key";
            $params[$key] = $id;
        }
    
        $qb = $this->connection->createQueryBuilder(); // tạo mới QueryBuilder
    
        $qb->select('p.id_product', 'p.product_name', 'p.price', 'c.quantity', 'c.product_id')
            ->from('products', 'p')
            ->innerJoin('p', 'cart', 'c', 'p.id_product = c.product_id')
            ->where('c.user_id = :user_id')
            ->andWhere(
                $qb->expr()->in('c.product_id', $productPlaceholders)
            );
    
        $stmt = $this->connection->executeQuery($qb->getSQL(), $params);
    
        return $stmt->fetchAllAssociative();
    }
    
    
    function createOrders($id_user) {
        $this->connection->insert('orders', [
            'id_user'    => $id_user,
            'total'     => $_POST['total'],
            'payment'   => $_POST['payment'],
            'status'    => 1,
            'created_at' => (new \DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh')))->format('Y-m-d H:i:s'),
            'updated_at' => (new \DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh')))->format('Y-m-d H:i:s'),

        ]);
    
        return $this->connection->lastInsertId();
    }
    
    function getCartByIdUser($id_user) {
        $qb = $this->queryBuilder;
    
        $qb
            ->select('c.*','p.price')
            ->from('cart','c')
            ->join('c','products','p','p.id_product = c.product_id')
            ->where('user_id = :user_id')
            ->setParameter('user_id', $id_user);
    
        $stmt = $this->connection->executeQuery($qb->getSQL(), $qb->getParameters());
    
        return $stmt->fetchAllAssociative();
    }
    
    function addOrdersDetail($id_orders, $id_pro, $price, $quantity) {
        $this->connection->insert('order_details', [
            'order_id' => $id_orders,
            'pro_id'    => $id_pro,
            'quantity'  => $quantity,
            'created_at' => (new \DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh')))->format('Y-m-d H:i:s'),
        ]);
    
        return true;
    }
    
    // function reduceStock($pro_id, $quantity) {
    //    $stmt = $this->queryBuilder
    // ->update('products')
    // ->set('quantity', 'quantity - :quantity')
    // ->where('id_product = :id_product')
    // ->setParameters([
    //     'quantity' => $quantity,
    //     'id_product' => $pro_id
    // ]);

    // $stmt = $this->connection->executeQuery($stmt->getSQL(), $stmt->getParameters());
    // return true;
    // }

    //fix reduce
    // function reduceStock($pro_id, $quantity) {
    //     // Kiểm tra quantity hợp lệ
    //     if (!is_numeric($quantity) || $quantity <= 0) {
    //         throw new Exception("Số lượng không hợp lệ: $quantity");
    //     }
    
    //     // Kiểm tra số lượng tồn kho hiện tại
    //     $currentStock = $this->connection->fetchOne(
    //         'SELECT quantity FROM products WHERE id_product = :id_product',
    //         ['id_product' => $pro_id]
    //     );
    
    //     if ($currentStock === false) {
    //         throw new Exception("Không tìm thấy sản phẩm với id_product = $pro_id");
    //     }
    //     if ($currentStock < $quantity) {
    //         throw new Exception("Số lượng tồn kho không đủ cho sản phẩm id_product = $pro_id");
    //     }
    
    //     // Cập nhật số lượng
    //     $stmt = 'UPDATE products SET quantity = quantity - :quantity WHERE id_product = :id_product';
    //     $this->connection->executeStatement($stmt, [
    //         'quantity' => $quantity,
    //         'id_product' => $pro_id
    //     ]);
    
    //     return true;
    // }
    
    
    function clearCart($id_user, $pro_id) {
        $this->connection->delete('cart', [
            'user_id' => $id_user,
            'product_id'  => $pro_id,
        ]);
    
        return true;
    }

    function reduceStock($pro_id, $quantity) {
        if (!is_numeric($quantity) || $quantity <= 0) {
            throw new Exception("Số lượng không hợp lệ: $quantity");
        }
    
        // Lấy số lượng tồn kho
        $currentStock = $this->connection->fetchAssociative(
            'SELECT quantity FROM products WHERE id_product = :id_product',
            ['id_product' => $pro_id]
        );
    
        if ($currentStock === false) {
            throw new Exception("Không tìm thấy sản phẩm với id_product = $pro_id");
        }
        if ($currentStock < $quantity) {
            throw new Exception("Số lượng tồn kho không đủ cho sản phẩm id_product = $pro_id");
        }
    
        // Trừ số lượng
        $this->connection->executeStatement(
            'UPDATE products SET quantity = quantity - :quantity WHERE id_product = :id_product',
            ['quantity' => $quantity, 'id_product' => $pro_id]
        );
    
        return true;
    }
    
    
}
?>