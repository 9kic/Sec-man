<?php

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // إنشاء الكود OTP
    $otp = mt_rand(100000, 999999);

    // الاتصال بقاعدة البيانات (يجب تغيير المعلومات وفقًا لمعلومات الاتصال الخاصة بك)
    $dbHost = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'otpbase';

    $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // التحقق مما إذا كان البريد الإلكتروني موجودًا بالفعل في قاعدة البيانات
    $sql = "SELECT * FROM otpbase WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // إذا كان البريد الإلكتروني موجودًا، تحديث الكود OTP
        $sql = "UPDATE otpbase SET otp = '$otp' WHERE email = '$email'";

        if ($conn->query($sql) === TRUE) {
            $conn->close();

            // إعداد وإرسال البريد الإلكتروني مع الكود OTP
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'email';
            $mail->Password   = 'app pasword';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;
            $mail->setFrom('email');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'HELLO IM DATA MAN';
            $mail->Body    = 'This is the code for your account <br>' . $otp;

            if ($mail->send()) {
                // إرسال بنجاح - نقل المستخدم إلى صفحة otp.php
                header("Location: otp.php?key=$otp");
                exit(); // تأكد من إيقاف تنفيذ البرنامج بعد التوجيه
            } else {
                echo 'Message could not be sent.';
            }
        } else {
            echo 'Error: ' . $sql . '<br>' . $conn->error;
        }
    } else {
        // إذا لم يكن البريد الإلكتروني موجودًا، إخراج رسالة
        echo 'البريد الإلكتروني غير موجود. <a href="login.php">الرجوع إلى صفحة تسجيل الدخول</a>';
    }

    $conn->close();
}
     ?> 