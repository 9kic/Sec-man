<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // اقرأ الكود OTP المدخل من النموذج
    $enteredOTP = $_POST["otpcode"];

    // توصيل إلى قاعدة البيانات والاستعلام عن الكود OTP
   

    $conn = new mysqli('localhost', 'root', '', 'otpbase');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM otpbase WHERE otp = '$enteredOTP'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // الكود OTP متطابق، يمكن توجيه المستخدم إلى صفحة أخرى
        header("Location: titlevido.php");
        exit();
    } /*else {
        // الكود OTP غير متطابق، عرض رسالة خطأ
        echo "رسالة خطأ هنا";
    }*/

    else {
        // الكود OTP غير متطابق، قم بتعيين رسالة الخطأ في متغير
        $error_message = "رمز OTP غير صحيح. يرجى المحاولة مرة أخرى.";

        // عرض الرسالة على نفس صفحة otp.php
        include('otp.php');
    }


    $conn->close();
}
?>