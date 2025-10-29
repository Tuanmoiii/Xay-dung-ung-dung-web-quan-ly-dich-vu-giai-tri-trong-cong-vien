<?php
/**
 * ===============================
 *  HỆ THỐNG QUẢN LÝ DỊCH VỤ GIẢI TRÍ CÔNG VIÊN
 *  Module: AUTHENTICATION FUNCTIONS
 *  Mô tả: Xử lý đăng nhập, đăng xuất, kiểm tra session
 * ===============================
 */

/**
 * Kiểm tra xem user đã đăng nhập chưa
 * Nếu chưa, chuyển hướng về trang login
 *
 * @param string $redirectPath Đường dẫn chuyển hướng (mặc định: '../index.php')
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
 * Hàm đăng xuất người dùng
 * Xóa toàn bộ session và chuyển hướng
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
 * Lấy thông tin người dùng hiện tại (từ session)
 *
 * @return array|null
 */
function getCurrentUser() {
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (isset($_SESSION['user_id']) && isset($_SESSION['full_name'])) {
        return [
            'id' => $_SESSION['user_id'],
            'full_name' => $_SESSION['full_name'],
            'role_id' => $_SESSION['role_id'] ?? null,
            'role_name' => $_SESSION['role_name'] ?? null
        ];
    }
    return null;
}

/**
 * Kiểm tra nhanh trạng thái đăng nhập (không redirect)
 *
 * @return bool
 */
function isLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return isset($_SESSION['user_id']) && isset($_SESSION['full_name']);
}

/**
 * Xác thực đăng nhập người dùng
 *
 * @param mysqli $conn Kết nối MySQL
 * @param string $full_name Họ tên (dùng làm định danh đăng nhập)
 * @param string $password Mật khẩu gõ vào
 * @return array|false Trả về mảng user nếu đúng, false nếu sai
 */
function authenticateUser($conn, $full_name, $password) {
    $sql = "SELECT u.user_id, u.full_name, u.password_hash, u.role_id, r.role_name
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.role_id
            WHERE u.full_name = ? LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return false;

    mysqli_stmt_bind_param($stmt, "s", $full_name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && $user = mysqli_fetch_assoc($result)) {
        // ✅ Kiểm tra mật khẩu (nếu chưa mã hóa thì dùng so sánh thường)
        if (password_verify($password, $user['password_hash']) || $password === $user['password_hash']) {
            mysqli_stmt_close($stmt);
            return $user;
        }
    }

    mysqli_stmt_close($stmt);
    return false;
}

/**
 * Lưu thông tin user vào session sau khi đăng nhập thành công
 *
 * @param array $user Mảng thông tin người dùng
 */
function saveUserSession($user) {
    if (session_status() === PHP_SESSION_NONE) session_start();

    $_SESSION['user_id']   = $user['user_id'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['role_id']   = $user['role_id'];
    $_SESSION['role_name'] = $user['role_name'] ?? null;
}

?>
