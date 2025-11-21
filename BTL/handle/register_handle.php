<?php
session_start();
require_once '../functions/db_connection.php'; // ✅ đúng file chứa getDbConnection()

$conn = getDbConnection(); // ✅ gọi hàm để tạo kết nối

// Nhận dữ liệu từ form
$full_name = trim($_POST['full_name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$password = trim($_POST['password']);

// Kiểm tra dữ liệu đầu vào
if ($full_name === '' || $password === '' || $email === '' || $phone === '') {
    $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin!';
    header('Location: ../views/register.php');
    exit();
}

// Kiểm tra xem user đã tồn tại chưa (tránh tạo trùng nhiều bản ghi)
$check = $conn->prepare("SELECT user_id FROM users WHERE full_name = ? LIMIT 1");
$check->bind_param("s", $full_name);
$check->execute();
$res = $check->get_result();
if ($res && $res->fetch_assoc()) {
    $_SESSION['error'] = 'Tài khoản với tên này đã tồn tại. Vui lòng đăng nhập hoặc chọn tên khác.';
    header('Location: ../views/register.php');
    exit();
}

// Gán role_id = 4 (khách hàng)
$role_id = 4;

// 1️⃣ Thêm vào bảng users (lưu mật khẩu thô theo yêu cầu)
$stmt = $conn->prepare("INSERT INTO users (role_id, full_name, password_hash) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $role_id, $full_name, $password);
if (!$stmt->execute()) {
    $_SESSION['error'] = 'Lỗi khi tạo tài khoản.';
    header('Location: ../views/register.php');
    exit();
}
$user_id = $conn->insert_id;

// 2️⃣ Thêm vào bảng customers (theo cấu trúc hiện tại)
$stmt2 = $conn->prepare("INSERT INTO customers (full_name, email, phone) VALUES (?, ?, ?)");
$stmt2->bind_param("sss", $full_name, $email, $phone);
$stmt2->execute();
$customer_id = $conn->insert_id;

// 3️⃣ Lưu session
$_SESSION['user_id'] = $user_id;
$_SESSION['customer_id'] = $customer_id;
$_SESSION['role_id'] = $role_id;
$_SESSION['full_name'] = $full_name;

// 4️⃣ Chuyển hướng sau đăng ký
$_SESSION['success'] = 'Đăng ký thành công!';
header("Location: ../views/customer/index.php");
exit;
?>
