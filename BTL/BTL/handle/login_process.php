<?php
require_once("../functions/db_connection.php");
require_once("../functions/auth_functions.php");

session_start();

// ✅ Tạo kết nối CSDL
$conn = getDbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $password = trim($_POST['password']);

    // Kiểm tra dữ liệu nhập vào
    if ($full_name === '' || $password === '') {
        $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin!';
        header('Location: ../index.php');
        exit();
    }

    // ✅ Gọi hàm xác thực người dùng
    $user = authenticateUser($conn, $full_name, $password);

    if ($user) {
        saveUserSession($user);
        $_SESSION['success'] = 'Đăng nhập thành công!';
        header('Location: ../views/dashboard/index.php');
        exit();
    } else {
        $_SESSION['error'] = 'Sai thông tin đăng nhập!';
        header('Location: ../index.php');
        exit();
    }
}

// ✅ Đóng kết nối sau khi xử lý
mysqli_close($conn);
?>
