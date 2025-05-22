<?php
session_start();
$message = ''; // متغير لتخزين الرسائل النصية

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "register";

    // إنشاء اتصال
    $conn = new mysqli($servername, $username, $password, $dbname);

    // التحقق من الاتصال
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // استلام البيانات من النموذج
    $email = $_POST['email'];

    // تشفير البريد الإلكتروني
    $hashed_email = hash('sha256', $email);

    // التحقق من وجود البريد الإلكتروني وكلمة المرور
    $sql = "SELECT * FROM users WHERE email='$hashed_email'";
    $result = $conn->query($sql);
    

    if ($result->num_rows > 0) {
        // جلب البيانات من قاعدة البيانات
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // تحويل المستخدم إلى صفحة main.html إذا كانت بيانات الدخول صحيحة
            header("Location:send.php");
            exit;
        } 
    } else {
        $_SESSION['message'] = 'البريد الإلكتروني غير موجود.';
    }

    // غلق الاتصال
    $conn->close();
    header("Location: send_rest_pass.php");
    exit;
}

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // إزالة الرسالة بعد عرضها
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحة تسجيل الدخول</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Handjet:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="all.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <div class="logo">
                <img src="imges/logo.png" alt="شعار الموقع">
            </div>
            <form action="send_rest_pass.php" method="post" onsubmit="return validateForm()">
                <input type="email" name="email" placeholder="البريد الإلكتروني">
                <input type="submit" value="اعادة تعين كلمة المرور">
            </form>
            <p class="register-link">الرجوع الى صفحة تسجيل الدخول؟ <a class="new" href="login.php">اضغط هنا </a></p>

        </div>
    </div>

   
    <script> function validateForm() {
        const email = document.querySelector('input[type="email"]').value;

        if ( email === "" ) {
            alert("الرجاء مل جميع الحقول قبل الإرسال.");
            return false; 
        }

        return true; 
    }</script>


</body>
</html>