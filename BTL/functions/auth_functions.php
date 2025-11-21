<?php
/**
 * ===============================
 *  HỆ THỐNG QUẢN LÝ DỊCH VỤ GIẢI TRÍ CÔNG VIÊN
 *  Module: AUTHENTICATION FUNCTIONS
 *  Mô tả: Xử lý đăng nhập, đăng xuất, kiểm tra session & phân quyền
 * ===============================
 */

/**
 * Kiểm tra xem user đã đăng nhập chưa
 */
function checkLogin($redirectPath = '../index.php') {
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['full_name'])) {
        $_SESSION['error'] = 'Bạn cần đăng nhập để truy cập trang này!';
        header('Location: ' . $redirectPath);
        exit();
    }
}

/**
 * ✅ Kiểm tra quyền Admin
 */
function checkAdmin($redirectPath = '../index.php') {
    checkLogin($redirectPath);
    if ($_SESSION['role_id'] != 1) { // role_id = 1 là Admin
        $_SESSION['error'] = 'Bạn không có quyền truy cập trang này!';
        header('Location: ../views/customer/index.php');
        exit();
    }
}

/**
 * ✅ Kiểm tra quyền Customer
 */
function checkCustomer($redirectPath = '../index.php') {
    checkLogin($redirectPath);
    if ($_SESSION['role_id'] != 4) { // role_id = 4 là Customer
        $_SESSION['error'] = 'Trang này chỉ dành cho khách hàng!';
        header('Location: ../views/dashboard/index.php');
        exit();
    }
}

/**
 * Hàm đăng xuất người dùng
 */
function logout($redirectPath = '../index.php') {
    if (session_status() === PHP_SESSION_NONE) session_start();
    session_unset();
    session_destroy();

    session_start();
    $_SESSION['success'] = 'Đăng xuất thành công!';
    header('Location: ' . $redirectPath);
    exit();
}

/**
 * Lấy thông tin người dùng hiện tại
 */
function getCurrentUser() {
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (isset($_SESSION['user_id']) && isset($_SESSION['full_name'])) {
        return [
            'id' => $_SESSION['user_id'],
            'full_name' => $_SESSION['full_name'],
            'role_id' => $_SESSION['role_id'],
            'role_name' => ($_SESSION['role_id'] == 1) ? 'admin' : 'customer'
        ];
    }
    return null;
}

/**
 * Kiểm tra nhanh trạng thái đăng nhập
 */
function isLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return isset($_SESSION['user_id']) && isset($_SESSION['full_name']);
}

/**
 * ✅ Xác thực người dùng
 */
function authenticateUser($conn, $full_name, $password) {
    $sql = "SELECT user_id, full_name, password_hash, role_id FROM users WHERE full_name = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return false;

    mysqli_stmt_bind_param($stmt, "s", $full_name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && $user = mysqli_fetch_assoc($result)) {
        // Nếu mật khẩu chưa mã hoá thì so sánh trực tiếp
        if (password_verify($password, $user['password_hash']) || $password === $user['password_hash']) {
            mysqli_stmt_close($stmt);
            return $user;
        }
    }

    mysqli_stmt_close($stmt);
    return false;
}

/**
 * ✅ Lưu thông tin user vào session
 */
function saveUserSession($user) {
    if (session_status() === PHP_SESSION_NONE) session_start();

    $_SESSION['user_id']   = $user['user_id'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['role_id']   = $user['role_id'];

    // Xác định role_name để tiện hiển thị
    $_SESSION['role_name'] = ($user['role_id'] == 1) ? 'admin' : 'customer';
}

/**
 * ✅ Đăng ký tài khoản mới (role mặc định là Customer)
 */
function registerUser($full_name, $password) {
    global $conn;
    $role_id = 4; // 4 = customer

    // 1️⃣ Tạo tài khoản người dùng
    $stmt = $conn->prepare("INSERT INTO users (role_id, full_name, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $role_id, $full_name, $password);
    if (!$stmt->execute()) return false;

    // 2️⃣ Lấy user_id vừa tạo
    $user_id = $conn->insert_id;

    // 3️⃣ Tạo bản ghi trong bảng customers
    // NOTE: một số schema không có cột user_id trong customers, dùng INSERT tương thích
    $stmt2 = $conn->prepare("INSERT INTO customers (full_name, email, phone) VALUES (?, '', '')");
    $stmt2->bind_param("s", $full_name);
    $stmt2->execute();

    return true;
}

?>

