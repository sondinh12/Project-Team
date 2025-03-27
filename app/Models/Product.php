<?php
namespace App\Models;
use App\Models\Model;

class Product extends Model {
    public function index(){
        $stmt = $this->queryBuilder->select('p.*,c.category_name as cate_name')->from('products','p')
        ->leftJoin('p','categories','c','p.category_id = c.id_category')->orderBy('id_product','DESC');
        return $stmt->fetchAllAssociative();
    }

    public function create($imageLink){
        $stmt = $this->queryBuilder->insert('products')
        ->values([
            'product_name'=>':product_name',
            'product_img'=>':product_img',
            'price'=>':price',
            'quantity'=>':quantity',
            'description'=>':description',
            'category_id'=>':category_id',
            'status'=>':status',
            'created_at'=>'NOW()',
            'updated_at'=>'NOW()'
        ])
        ->setParameters([
            'product_name'=>$_POST['product_name'],
            'product_img'=>$imageLink,
            'price'=>$_POST['price'],
            'quantity'=>$_POST['quantity'],
            'description'=>$_POST['description'],
            'category_id'=>$_POST['category_id'],
            'status'=>'active',
        ]);
        $this->connection->executeStatement($stmt->getSQL(),$stmt->getParameters());       
    }

    public function getCate(){
        $stmt = $this->queryBuilder->select('*')->from('categories');
        return $stmt->fetchAllAssociative();
    }

    public function getProOld($id){
        $stmt = $this->queryBuilder->select('p.*','c.category_name as cate_name')->from('products','p')->where('id_product = :id_product')
        ->setParameter('id_product',$id)
        ->leftJoin('p','categories','c','c.id_category = p.category_id');
        return $stmt->fetchAssociative();
    }

    public function update($id,$imageLink){
        $stmt = $this->queryBuilder->update('products')->where('id_product = :id_product')
        ->set('product_name',':product_name')
        ->set('product_img',':product_img')
        ->set('price',':price')
        ->set('quantity',':quantity')
        ->set('description',':description')
        ->set('category_id',':category_id')
        ->set('status',':status')
        ->set('created_at','NOW()')
        ->set('updated_at','NOW()')
        ->setParameters([
            'product_name'=>$_POST['product_name'],
            'product_img'=>$imageLink,
            'price'=>$_POST['price'],
            'quantity'=>$_POST['quantity'],
            'description'=>$_POST['description'],
            'category_id'=>$_POST['category_id'],
            'status'=>$_POST['status'],
            'id_product'=>$id
        ]);
        $this->connection->executeStatement($stmt->getSQL(),$stmt->getParameters());  
    }

    public function destroy($id){
        $stmt = $this->queryBuilder->delete('products')->where('id_product = :id_product')
        ->setParameter('id_product',$id);
        $this->connection->executeStatement($stmt->getSQL(),$stmt->getParameters());  
    }
}
?>