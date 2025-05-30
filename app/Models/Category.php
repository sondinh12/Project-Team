<?php
namespace App\Models;

use App\Models\Model;
class Category extends Model{
    public function index(){
        $stmt = $this->queryBuilder->select('*')->from('categories')->orderBy('id_category','DESC');
        return $stmt->fetchAllAssociative();
    }

    public function create(){
        $stmt = $this->queryBuilder->insert('categories')->values([
            'category_name'=>':category_name',
            'created_at'=>'NOW()',
            'updated_at'=>'NOW()'
        ])
        ->setParameters([
            'category_name'=>$_POST['category_name'],
        ]);
        $this->connection->executeStatement($stmt->getSQL(),$stmt->getParameters());       
    }

    public function cateOld($id){
        $stmt = $this->queryBuilder->select('*')->from('categories')->where('id_category = :id_category')
        ->setParameter('id_category',$id)
        ->executeQuery();
        return $stmt->fetchAssociative();
    }

    public function update($id){
        $stmt = $this->queryBuilder->update('categories')
        ->where('id_category = :id_category')
        ->set('category_name',':category_name')
        ->set('updated_at','NOW()')
        ->setParameters([
            'category_name'=>$_POST['category_name'],
            'id_category'=>$id
        ]);
        $this->connection->executeStatement($stmt->getSQL(),$stmt->getParameters());    
    }

    public function countProducts($id){
        $stmt = $this->queryBuilder->select('COUNT(*) AS total')->from('products')->where('category_id = :id')
        ->setParameter('id',$id)
        ->executeQuery();
        return $stmt->fetchOne();
    }

    public function destroy($id){
        $stmt = $this->queryBuilder->delete('categories')->where('id_category = :id_category')
        ->setParameter('id_category',$id);
        $this->connection->executeStatement($stmt->getSQL(),$stmt->getParameters()); 
    }

    public function searchByName($cate_name){
        $stmt = $this->queryBuilder->select('*')->from('categories')
            ->where('category_name LIKE :category_name')
            ->setParameter('category_name', "%$cate_name%")
            ->executeQuery();
        return $stmt->fetchAllAssociative();
    }
}
?>