<?php
session_start();
require_once __DIR__ . '/../functions/customers_functions.php';
require_once __DIR__ . '/../functions/auth_functions.php';
checkLogin();

function redirect_list($message = null, $success = true) {
    if ($message) {
        if ($success) $_SESSION['success'] = $message;
        else $_SESSION['error'] = $message;
    }
    header('Location: ../views/customers/list.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create
    if (isset($_POST['create'])) {
        $name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if ($name === '') redirect_list('Tên khách hàng không được để trống', false);

        $ok = addCustomer($name, $email, $phone);
        if ($ok) redirect_list('Tạo khách hàng thành công');
        redirect_list('Tạo khách hàng thất bại', false);
    }

    // Update
    if (isset($_POST['update'])) {
        $id = intval($_POST['customer_id'] ?? 0);
        $name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if ($id <= 0) redirect_list('ID khách hàng không hợp lệ', false);
        if ($name === '') redirect_list('Tên khách hàng không được để trống', false);

        $ok = updateCustomer($id, $name, $email, $phone);
        if ($ok) redirect_list('Cập nhật khách hàng thành công');
        redirect_list('Cập nhật khách hàng thất bại', false);
    }

    // Delete
    if (isset($_POST['delete'])) {
        $id = intval($_POST['customer_id'] ?? 0);
        if ($id <= 0) redirect_list('ID khách hàng không hợp lệ', false);
        $ok = deleteCustomer($id);
        if ($ok) redirect_list('Xóa khách hàng thành công');
        redirect_list('Xóa khách hàng thất bại', false);
    }
}

header('Location: ../views/customers/list.php');
exit();

?>
