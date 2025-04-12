<?php

namespace App\Models;

use App\Models\Model;

class Client extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Lấy danh sách sản phẩm
     *
     * @return array
     */
    public function index()
    {
        // Chỉ lấy sản phẩm có trạng thái 'active'
        $stmt = $this->queryBuilder
            ->select('*')
            ->from('products')
            ->where('status = :status')
            ->orderBy('id_product', 'DESC')
            ->setParameter('status', 'active'); // Truyền trạng thái 'active'

        return $stmt->fetchAllAssociative();
    }
    public function getCategories()
    {
        $stmt = $this->queryBuilder->select('*')->from('categories')->orderBy('id_category', 'DESC');
        return $stmt->fetchAllAssociative();
    }


    public function detail($id)
    {
        $stmt = $this->queryBuilder->select('*')->where('id_product = :id_product')->from('products')
            ->setParameter('id_product', $id);
        return $stmt->fetchAssociative();
    }

    public function getCateIdPro($id)
    {
        $stmt = $this->queryBuilder->select('category_id')->from('products')->where('id_product = :id_product')
            ->setParameter('id_product', $id);
        $category = $stmt->fetchAssociative();
        return $category ? $category['category_id'] : null;
    }

    public function getProductforCate($categoryId)
    {
        $stmt = $this->queryBuilder->select('*')->from('products')
            ->where('category_id = :categoryId')->setParameter('categoryId', $categoryId);
        return $stmt->fetchAllAssociative();
    }

    public function login()
    {
        $stmt = $this->queryBuilder->select('*')->from('users')->where('user_name = :user_name AND password = :password')
            ->setParameters([
                'user_name' => $_POST['user_name'],
                'password' => $_POST['password']
            ]);
        return $stmt->fetchAssociative();
    }
}
