<?php
session_start();
require_once '../functions/db_connection.php';
// Tạo kết nối DB và đặt vào biến toàn cục $conn vì auth_functions.php sử dụng global $conn
$conn = getDbConnection();
require_once '../functions/auth_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Mật khẩu nhập lại không khớp!";
        header("Location: ../views/register.php");
        exit();
    }

    if (registerUser($full_name, $password)) {
        $_SESSION['success'] = "Đăng ký thành công! Hãy đăng nhập.";
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['error'] = "Đăng ký thất bại. Vui lòng thử lại.";
        header("Location: ../views/register.php");
        exit();
    }
}
?>
