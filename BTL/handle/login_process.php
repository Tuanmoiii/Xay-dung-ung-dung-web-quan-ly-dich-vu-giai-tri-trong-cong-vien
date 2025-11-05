<?php
require_once("../functions/db_connection.php");
require_once("../functions/auth_functions.php");

session_start();

// ✅ Kết nối cơ sở dữ liệu
$conn = getDbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $password  = trim($_POST['password']);

    // Kiểm tra dữ liệu nhập
    if ($full_name === '' || $password === '') {
        $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin!';
        header('Location: ../index.php');
        exit();
    }

    // ✅ Gọi hàm xác thực người dùng
    $user = authenticateUser($conn, $full_name, $password);

    if ($user) {
        // ✅ Lưu thông tin cơ bản
        $_SESSION['user_id']   = $user['user_id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role_id']   = $user['role_id'];

        // ✅ Nếu là khách hàng (role_id = 4) → tìm customer_id
        if ($user['role_id'] == 4) {
            $stmt = $conn->prepare("SELECT customer_id FROM customers WHERE full_name = ? LIMIT 1");
            $stmt->bind_param("s", $full_name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $_SESSION['customer_id'] = $row['customer_id'];
            } else {
                // ⚠️ Nếu chưa có bản ghi trong customers → tự tạo luôn
                $insert = $conn->prepare("INSERT INTO customers (full_name) VALUES (?)");
                $insert->bind_param("s", $full_name);
                $insert->execute();
                $_SESSION['customer_id'] = $conn->insert_id;
            }
        }

        // ✅ Phân quyền đăng nhập
        if ($user['role_id'] == 1) {
            $_SESSION['success'] = 'Đăng nhập thành công (Admin)!';
            header('Location: ../views/dashboard/index.php');
            exit();
        }

        if ($user['role_id'] == 4) {
            $_SESSION['success'] = 'Đăng nhập thành công (Khách hàng)!';
            header('Location: ../views/customer/index.php');
            exit();
        }

        // ✅ Trường hợp role khác (nếu có)
        $_SESSION['error'] = 'Không xác định được vai trò người dùng!';
        header('Location: ../index.php');
        exit();
    } 
    else {
        $_SESSION['error'] = 'Sai thông tin đăng nhập!';
        header('Location: ../index.php');
        exit();
    }
}

// ✅ Đóng kết nối
mysqli_close($conn);
?>
