<?php

namespace App\Middlewares;

class AuthMiddleware
{
    public static function checkAdmin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập!";
            header('Location: ' . $_ENV['BASE_URL'] . 'home');
            exit;
        }
    }

    public static function log()
    {
        header('Location: ' . $_ENV['BASE_URL'] . 'login');
        exit;
    }
}
