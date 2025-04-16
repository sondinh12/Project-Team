<?php

namespace App\Controllers;

use App\Common\Blade;
use Rakit\Validation\Validator;
use App\Models\Auth;

require_once 'send_email.php';
class AuthController
{
    protected $authModel;

    public function __construct()
    {
        $this->authModel = new Auth();
    }

    public function auth()
    {
        $validator = new Validator();

        // Xử lý đăng nhập
        if (isset($_POST['btn_login'])) {
            $validation = $validator->make([
                'user_name' => $_POST['user_name'] ?? '',
                'pass'      => $_POST['pass'] ?? '',
            ], [
                'user_name' => 'required|min:3',
                'pass'      => 'required|min:6',
            ]);
            $validation->validate();

            if ($validation->fails()) {
                $_SESSION['errors'] = $validation->errors()->toArray();
                $_SESSION['old'] = $_POST;
                header('Location: ' . $_ENV['BASE_URL'] . 'login');
                exit;
            }

            $user = $this->authModel->findUserByUsername($_POST['user_name']);
            if (!$user || !password_verify($_POST['pass'], $user['password'])) {
                $_SESSION['errors'] = ['login' => ['Tên đăng nhập hoặc mật khẩu không đúng']];
                $_SESSION['old'] = $_POST;
                header('Location: ' . $_ENV['BASE_URL'] . 'login');
                exit;
            }
            if ($this->authModel->checkStatusUser($_POST['user_name']) != 'active') {
                $_SESSION['errors'] = ['login' => ['Tài khoản của bạn đã bị khóa.']];
                $_SESSION['old'] = $_POST;
                header('Location: ' . $_ENV['BASE_URL'] . 'login');
                exit;
            }
            $_SESSION['user'] = $user;
            $_SESSION['success'] = 'Đăng nhập thành công.';

            header('Location: ' . $_ENV['BASE_URL'] . 'home');
            exit;
        }

        if (isset($_POST['btn_register'])) {
            $validation = $validator->make([
                'name'              => $_POST['name'] ?? '',
                'user_name'         => $_POST['user_name'] ?? '',
                'email'             => $_POST['email'] ?? '',
                'password'          => $_POST['password'] ?? '',
                'confirm_password'  => $_POST['confirm_password'] ?? '',
            ], [
                'name'              => 'required|min:3',
                'user_name'         => 'required|min:3',
                'email'             => 'required|email',
                'password'          => 'required|min:6',
                'confirm_password'  => 'required|same:password',
            ]);
            $validation->validate();

            if ($validation->fails()) {
                $_SESSION['errors'] = $validation->errors()->toArray();
                $_SESSION['old'] = $_POST;
                $_SESSION['active_form'] = 'register';
                header('Location: ' . $_ENV['BASE_URL'] . 'login');
                exit;
            }

            if ($this->authModel->findUserByUsername($_POST['user_name'])) {
                $_SESSION['errors'] = ['user_name' => ['Tên đăng nhập đã tồn tại']];
                $_SESSION['old'] = $_POST;
                $_SESSION['active_form'] = 'register';
                header('Location: ' . $_ENV['BASE_URL'] . 'login');
                exit;
            }

            if ($this->authModel->findUserByEmail($_POST['email'])) {
                $_SESSION['errors'] = ['email' => ['Email đã được sử dụng']];
                $_SESSION['old'] = $_POST;
                $_SESSION['active_form'] = 'register';
                header('Location: ' . $_ENV['BASE_URL'] . 'login');
                exit;
            }

            $this->authModel->registerUser();
            $_SESSION['user'] = $this->authModel->findUserByUsername($_POST['user_name']);
            $_SESSION['success'] = 'Đăng ký thành công.';
            header('Location: ' . $_ENV['BASE_URL'] . 'home');
            exit;
        }

        // Xử lý gửi OTP
        if (isset($_POST['btn_getotp'])) {
            $validation = $validator->make([
                'email_getotp' => $_POST['email_getotp'] ?? ''
            ], [
                'email_getotp' => 'required|email'
            ]);
            $validation->validate();

            if ($validation->fails()) {
                $_SESSION['errors'] = $validation->errors()->toArray();
                $_SESSION['old'] = $_POST;
                $_SESSION['active_form'] = 'forgotpass';
                header('Location: ' . $_ENV['BASE_URL'] . 'login');
                exit;
            }

            $email = trim($_POST['email_getotp']);
            $_SESSION['email'] = $email;

            if ($this->authModel->checkEmail($email) <= 0) {
                $_SESSION['errors'] = ['email_getotp' => ['Email không tồn tại trong hệ thống.']];
                $_SESSION['old'] = $_POST;
                $_SESSION['active_form'] = 'forgotpass';
                header('Location: ' . $_ENV['BASE_URL'] . 'login');
                exit;
            }

            $id_user = $this->authModel->getuserID($email);
            $existingToken = $this->authModel->tokenExists($id_user);
            $requestCount = $this->authModel->getCount($id_user);

            if ($requestCount >= 3) {
                $_SESSION['errors'] = ['email_getotp' => ['Bạn đã yêu cầu OTP quá nhiều lần. Vui lòng chờ 20 phút.']];
                $_SESSION['old'] = $_POST;
                $_SESSION['active_form'] = 'forgotpass';
                header('Location: ' . $_ENV['BASE_URL'] . 'login');
                exit;
            }

            $otp = rand(100000, 999999);
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));
            $message = "Mã OTP của bạn là: $otp";

            if (sendResetEmail($email, $message)) {
                $this->authModel->createOrUpdateResetToken($id_user, $otp, $expiry, $existingToken);
                $_SESSION['success'] = "Mã OTP đã được gửi đến email của bạn.";
                $_SESSION['form_change'] = '1';
            } else {
                $_SESSION['errors'] = ['email_getotp' => ['Lỗi gửi email. Vui lòng thử lại.']];
            }

            $_SESSION['active_form'] = 'forgotpass';
            header('Location: ' . $_ENV['BASE_URL'] . 'login');
            exit;
        }


        // Xử lý đặt lại mật khẩu
        if (isset($_POST['btn_changepass'])) {
            $token = $_POST['otp'] ?? '';
            $password = $_POST['password'] ?? '';
            $email = $_SESSION['email'] ?? '';

            $validation = $validator->make([
                'otp' => $token,
                'password' => $password
            ], [
                'otp' => 'required|numeric',
                'password' => 'required|min:6'
            ]);
            $validation->validate();

            if ($validation->fails()) {
                $_SESSION['errors'] = $validation->errors()->toArray();
                $_SESSION['active_form'] = 'forgotpass';
                $_SESSION['form_change'] = '1';
                header('Location: ' . $_ENV['BASE_URL'] . 'login');
                exit;
            }

            $id_user = $this->authModel->getuserID($email);
            if ($this->authModel->checkToken($token) > 0) {
                if ($this->authModel->changePass($email, $password)) {
                    $this->authModel->deleteToken($id_user);
                    $_SESSION['message'] = "Cập nhật mật khẩu thành công.";
                    $_SESSION['user'] = $this->authModel->findUserByEmail($email);
                    unset($_SESSION['email']);
                    header('Location: ' . $_ENV['BASE_URL'] . 'home');
                    exit;
                } else {
                    $_SESSION['message_reset'] = "Lỗi khi cập nhật mật khẩu.";
                    // $_SESSION['form-change'] = '1';
                }
            } else {
                $_SESSION['message_reset'] = "Mã OTP không hợp lệ hoặc đã hết hạn.";
                $_SESSION['form_change'] = '1';
            }
            $_SESSION['form-form_change'] = '1';
            $_SESSION['active_form'] = 'forgotpass';
            header('Location: ' . $_ENV['BASE_URL'] . 'login');
            exit;
        }

        // Hiển thị giao diện login với các dữ liệu session
        Blade::render('auth.auth', [
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? [],
            'active_form' => $_SESSION['active_form'] ?? 'login',
            'success' => $_SESSION['success'] ?? null,
            'message_reset' => $_SESSION['message_reset'] ?? null,
            'form_change' => $_SESSION['form_change'] ?? null,
            'message' => $_SESSION['message'] ?? null,
        ]);
        unset($_SESSION['errors'], $_SESSION['old'], $_SESSION['active_form'], $_SESSION['success'], $_SESSION['message_reset'], $_SESSION['message'], $_SESSION['form_change']);
    }

    public function logout()
    {
        unset($_SESSION['user']);
        $_SESSION['success'] = 'Đăng xuất thành công.';
        header('Location: ' . $_ENV['BASE_URL'] . 'home');

        exit;
    }
}
