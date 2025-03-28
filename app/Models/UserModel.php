<?php

namespace App\Models;

use App\Models\Model;

class UserModel extends Model
{
    public function index()
    {
        return $this->queryBuilder->select('*')->from('users')
            ->orderBy('id_user', 'DESC')->fetchAllAssociative();
    }
    public function create($imageName)
    {
        $stmt = $this->queryBuilder->insert('users')->values([
            'name'       => ':name',
            'user_name'  => ':user_name',
            'email'      => ':email',
            'phone'      => ':phone',
            'password'   => ':password',
            'user_img'   => ':user_img',
            'address'    => ':address',
            'status'     => ':status',
            'role'       => ':role',
            'created_at' => 'NOW()',
            'updated_at' => 'NOW()'
        ])
            ->setParameters([
                'name'       => $_POST['name'],
                'user_name'  => $_POST['user_name'],
                'email'      => $_POST['email'],
                'phone'      => $_POST['phone'],
                'password'   => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'user_img'   => $imageName,
                'address'    => $_POST['address'],
                'status'     => $_POST['status'],
                'role'       => $_POST['role']
            ]);

        $this->connection->executeStatement($stmt->getSQL(), $stmt->getParameters());
    }
    public function FindUserByID($id)
    {
        return $this->queryBuilder->select('*')->from('users')->where('id_user = :id_user')->setParameter('id_user', $id)->fetchAssociative();
    }
    public function update($id, $imageName)
    {
        $stmt = $this->queryBuilder->update('users')
            ->set('name', ':name')
            ->set('email', ':email')
            ->set('phone', ':phone')
            ->set('address', ':address')
            ->set('updated_at', ':updated_at')
            ->set('user_img', ':user_img')
            ->set('role', ':role')
            ->set('status', ':status')
            ->where('id_user = :id_user')
            ->setParameters([
                'name'    => $_POST['name'],
                'email'   => $_POST['email'],
                'phone'   => $_POST['phone'],
                'address' => $_POST['address'],
                'user_img' => $imageName,
                'id_user' => $id,
                'role'    => $_POST['role'],
                'status'    => $_POST['status'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        $this->connection->executeStatement($stmt->getSQL(), $stmt->getParameters());
    }
    // public function destroy($id)
    // {
    //     $this->queryBuilder->delete('users')->where('id_user = :id_user')->setParameter('id_user', $id);
    //     $this->connection->executeStatement($this->queryBuilder->getSQL(), $this->queryBuilder->getParameters());
    // }
    public function changestatus($id, $newStatus)
    {
        $stmt = $this->queryBuilder->update('users')
            ->set('status', ':status')
            ->where('id_user = :id_user')
            ->setParameters([
                'id_user' => $id,
                'status' => $newStatus
            ]);
        $this->connection->executeStatement($stmt->getSQL(), $stmt->getParameters());
    }
}
