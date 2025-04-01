<?php
namespace App\Models;
use App\Models\Model;

class Client extends Model {
    public function index(){
        $stmt = $this->queryBuilder->select('*')->from('products');
        return $stmt->fetchAllAssociative();
    }

    public function getCategories(){
        $stmt = $this->queryBuilder->select('*')->from('categories')->orderBy('id_category','DESC');
        return $stmt->fetchAllAssociative();
    }

    public function detail($id){
        $stmt = $this->queryBuilder->select('*')->where('id_product = :id_product')->from('products')
        ->setParameter('id_product',$id);
        return $stmt->fetchAssociative();
    }
}
?>