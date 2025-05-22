<?php
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// تأكد من أن الطلب هو POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    // استلام البيانات من النموذج
    $full_name = trim($_POST['full_name']);
    $phone_number = trim($_POST['phone_number']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // التحقق من أن الحقول غير فارغة
    if (empty($full_name) || empty($phone_number) || empty($email) || empty($password)) {
        echo "Error: Please enter all required data before clicking the button.";
        exit();
    }

    // إعداد الاتصال بقاعدة بيانات المستخدمين
    $conn = new mysqli("localhost", "root", "", "register");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // تشفير البريد وكلمة المرور
    $hashed_email = hash('sha256', $email);
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // التحقق مما إذا كان البريد موجود مسبقاً
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $hashed_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "هذا البريد الإلكتروني مسجل بالفعل. <a href='login.php'>قم بالضغط على تسجيل الدخول</a>";
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    // إدخال المستخدم الجديد
    $stmt = $conn->prepare("INSERT INTO users (full_name, phone_number, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $phone_number, $hashed_email, $hashed_password);

    if (!$stmt->execute()) {
        echo "حدث خطأ أثناء التسجيل: " . $stmt->error;
        $stmt->close();
        $conn->close();
        exit();
    }

    $stmt->close();
    $conn->close();

    // الآن إرسال كود OTP إلى البريد

    // إعداد الاتصال بقاعدة بيانات OTP
    $conn = new mysqli("localhost", "root", "", "otpbase");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // إنشاء كود OTP عشوائي
    $otp = mt_rand(100000, 999999);

    // إدخال OTP والبريد الأصلي (غير مشفر) في قاعدة البيانات
    $stmt = $conn->prepare("INSERT INTO otpbase (email, otp) VALUES (?, ?)");
    $stmt->bind_param("si", $email, $otp);

    if (!$stmt->execute()) {
        echo "Error saving OTP: " . $stmt->error;
        $stmt->close();
        $conn->close();
        exit();
    }

    $stmt->close();
    $conn->close();

    // إرسال البريد الإلكتروني باستخدام PHPMailer
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'email';
        $mail->Password   = 'kryy ndmq fytw dckp'; // ⚠️ احرص على استخدام كلمة مرور تطبيق
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        $mail->setFrom('email', 'Data Man');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'كود التحقق لحسابك';
        $mail->Body    = "مرحبا $full_name، <br><br> كود التحقق الخاص بك هو: <strong>$otp</strong><br><br> يرجى إدخاله في صفحة التحقق.";

        $mail->send();

        // إعادة التوجيه إلى صفحة OTP
        header("Location: otp.php?key=$otp");
        exit();
    } catch (Exception $e) {
        echo "فشل إرسال البريد الإلكتروني: {$mail->ErrorInfo}";
    }
}
?>
