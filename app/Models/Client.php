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

    public function getUser($id_user)
    {
        $stmt = $this->queryBuilder->select('*')->from('users')->where('users.id_user = :id_user')
            ->setParameter('id_user', $id_user);
        return $stmt->fetchAssociative();
    }
    public function getBill($id_user)
    {
        $stmt = $this->queryBuilder
            ->select('*')
            ->from('orders')
            ->where('orders.id_user = :id_user')
            ->orderBy('orders.created_at', 'DESC')
            ->setParameter('id_user', $id_user);

        return $stmt->fetchAllAssociative();
    }
    public function updateUserInfo($userId, $phone, $address, $password, $image)
    {
        $data = [];

        if ($phone) {
            $data['phone'] = $phone;
        }
        if ($address) {
            $data['address'] = $address;
        }
        if ($password) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }
        if ($image) {
            $data['user_img'] = $image;
        }

        if (!empty($data)) {
            $stmt = $this->queryBuilder
                ->update('users');

            // Gọi set từng cặp key => :param
            foreach ($data as $column => $value) {
                $stmt->set($column, ':' . $column);
            }

            $stmt->where('id_user = :id_user');
            $stmt->setParameter('id_user', $userId);

            // Gán giá trị cho mỗi tham số
            foreach ($data as $column => $value) {
                $stmt->setParameter($column, $value);
            }

            $stmt->executeStatement();
        }
    }


    public function find($id_order)
    {
        $stmt = $this->queryBuilder
            ->select('*')
            ->from('orders')
            ->where('id_order = :id_order')
            ->setParameter('id_order', $id_order);

        return $stmt->executeQuery()->fetchAssociative();
    }

    public function getItems($id_order)
    {
        $stmt = $this->queryBuilder
            ->select('od.*, p.product_name AS product_name', 'p.price AS product_price')
            ->from('order_details', 'od')
            ->innerJoin('od', 'products', 'p', 'od.pro_id = p.id_product')
            ->where('od.order_id = :id_order')
            ->setParameter('id_order', $id_order);

        return $stmt->executeQuery()->fetchAllAssociative();
    }
    public function cancelorder($id_order)
    {
        $stmt = $this->queryBuilder
            ->update('orders')
            ->set('status', ':status')
            ->where('id_order = :id_order')
            ->setParameter('status', 'cancelled')
            ->setParameter('id_order', $id_order);

        return $stmt->executeStatement();
    }
}
