<?php
session_start();
require_once __DIR__ . '/../functions/auth_functions.php';
require_once __DIR__ . '/../functions/customers_functions.php';
checkLogin();

$user = getCurrentUser();
$user_id = $user['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/customer/profile.php');
    exit();
}

$customer_id = intval($_POST['customer_id'] ?? 0);
$name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if ($name === '') {
    $_SESSION['error'] = 'Tên không được để trống';
    header('Location: ../views/customer/profile.php');
    exit();
}

// If no explicit customer_id provided, try to resolve by user_id
if ($customer_id <= 0 && function_exists('getCustomerByUserId')) {
    $cust = getCustomerByUserId($user_id);
    if ($cust) $customer_id = $cust['customer_id'];
}

if ($customer_id <= 0) {
    // Could not find a customer record to update
    $_SESSION['error'] = 'Không tìm thấy hồ sơ khách hàng để cập nhật';
    header('Location: ../views/customer/profile.php');
    exit();
}

$ok = updateCustomer($customer_id, $name, $email, $phone);
if ($ok) {
    $_SESSION['success'] = 'Cập nhật thông tin thành công';
} else {
    $_SESSION['error'] = 'Cập nhật thông tin thất bại';
}

header('Location: ../views/customer/profile.php');
exit();
