<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';

function sendResetEmail($email, $message)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'duan1forgotpass@gmail.com';
        $mail->Password = 'qmks kqhm dsfz zvoo';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('duan1forgotpass@gmail.com', 'Your Website');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'OTP code to reset your password';
        $mail->Body    = "<p>$message</p>";
        $mail->AltBody = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Email không thể gửi. Lỗi: {$mail->ErrorInfo}";
        return false;
    }
}
