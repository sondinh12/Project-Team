<?php

namespace App\Models;

use App\Models\Model;
use Doctrine\DBAL\Connection;

class Auth extends Model
{
    public function registerUser()
    {
        $stmt = $this->queryBuilder
            ->insert('users')
            ->values([
                'name'        => ':name',
                'user_name'   => ':user_name',
                'email'       => ':email',
                'password'    => ':password',
                'role'        => ':role',
                'status'      => ':status',
                'created_at'  => ':created_at',
                'updated_at'  => ':updated_at',
            ]);

        $stmt->setParameters([
            'name'        => $_POST['name'],
            'user_name'   => $_POST['user_name'],
            'email'       => $_POST['email'],
            'password'    => password_hash($_POST['password'], PASSWORD_BCRYPT),
            'role'        => 'user',
            'status'      => 'active',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        $this->connection->executeStatement($stmt->getSQL(), $stmt->getParameters());
    }

    public function checkStatusUser($user_name)
    {
        $stmt = $this->queryBuilder
            ->select('status')
            ->from('users')
            ->where('user_name = :user_name')
            ->setParameter('user_name', $user_name)
            ->executeQuery();

        return $stmt->fetchOne();
    }
    public function findUserByUsername($user_name)
    {
        $stmt = $this->queryBuilder
            ->select('*')
            ->from('users')
            ->where('user_name = :user_name')
            ->setParameter('user_name', $user_name)
            ->executeQuery();

        return $stmt->fetchAssociative();
    }
    public function findUserByEmail($email)
    {
        $stmt = $this->queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery();

        return $stmt->fetchAssociative();
    }

    public function login($user_name, $password)
    {
        $user = $this->findUserByUsername($user_name);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function checkStatus($user_name)
    {
        $stmt = $this->queryBuilder
            ->select('status')
            ->from('users')
            ->where('user_name = :user_name')
            ->setParameter('user_name', $user_name)
            ->executeQuery();

        return $stmt->fetchOne();
    }

    public function checkRole($user_name)
    {
        $stmt = $this->queryBuilder
            ->select('role')
            ->from('users')
            ->where('user_name = :user_name')
            ->setParameter('user_name', $user_name)
            ->executeQuery();

        return $stmt->fetchOne();
    }

    public function getUserID($email)
    {
        $stmt = $this->queryBuilder
            ->select('id_user')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery();

        return $stmt->fetchOne();
    }

    public function changePassword($email, $newPassword)
    {
        return $this->queryBuilder
            ->update('users')
            ->set('password', ':password')
            ->where('email = :email')
            ->setParameters([
                'password' => password_hash($newPassword, PASSWORD_BCRYPT),
                'email' => $email
            ])
            ->executeStatement();
    }

    public function tokenExists($id_user)
    {
        return $this->queryBuilder
            ->select('t.reset_token', 't.id_user', 't.token_expiry') // chỉ rõ cột, tránh SELECT *
            ->from('tokens', 't')
            ->where('t.id_user = :id_user')
            ->setParameter('id_user', $id_user)
            ->fetchAssociative();
    }


    public function createOrUpdateResetToken($id_user, $otp, $expiry, $existingToken)
    {
        if ($existingToken) {
            // Tăng count bằng cách dùng biểu thức SQL thuần
            return $this->queryBuilder
                ->update('tokens')
                ->set('reset_token', ':otp')
                ->set('token_expiry', ':expiry')
                ->set('count', 'count + 1') // Trực tiếp viết biểu thức SQL
                ->where('id_user = :id_user')
                ->setParameters([
                    'otp' => $otp,
                    'expiry' => $expiry,
                    'id_user' => $id_user
                ])
                ->executeStatement();
        } else {
            return $this->queryBuilder
                ->insert('tokens')
                ->values([
                    'id_user' => ':id_user',
                    'reset_token' => ':reset_token',
                    'token_expiry' => ':token_expiry',
                    'count' => 1
                ])
                ->setParameters([
                    'id_user' => $id_user,
                    'reset_token' => $otp,
                    'token_expiry' => $expiry,
                ])
                ->executeStatement();
        }
    }

    public function deleteToken($id_user)
    {
        return $this->queryBuilder
            ->delete('tokens')
            ->where('id_user = :id_user')
            ->setParameter('id_user', $id_user)
            ->executeStatement();
    }

    public function checkToken($token)
    {
        $stmt = $this->queryBuilder
            ->select('*')
            ->from('tokens')
            ->where('reset_token = :token')
            ->andWhere('token_expiry > NOW()')
            ->setParameter('token', $token)
            ->executeQuery();

        return $stmt->rowCount();
    }

    public function getCount($id_user)
    {
        return $this->queryBuilder
            ->select('count')
            ->from('tokens', 't')
            ->where('t.id_user = :id_user')
            ->setParameter('id_user', $id_user)
            ->fetchOne();
    }


    public function checkEmail($email)
    {
        $stmt = $this->queryBuilder
            ->select('COUNT(*)')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery();

        return $stmt->fetchOne();
    }
    public function changePass($email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        return $this->queryBuilder
            ->update('users')
            ->set('password', ':password')
            ->where('email = :email')
            ->setParameters([
                'password' => $hashedPassword,
                'email'    => $email
            ])
            ->executeStatement();
    }
}
