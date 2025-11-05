<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
require_once __DIR__ . '/../../functions/services_functions.php';
require_once __DIR__ . '/../../functions/schedules_functions.php';
require_once __DIR__ . '/../../functions/bookings_functions.php';

// ✅ Kiểm tra đăng nhập
checkLogin();

// ✅ Kiểm tra quyền (role_id = 4 là Customer)
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 4) {
    $_SESSION['error'] = 'Bạn không có quyền truy cập trang khách hàng!';
    header('Location: ../dashboard/index.php');
    exit();
}

// ✅ Lấy thông tin người dùng hiện tại
$user = getCurrentUser();
$customer_id = $user['id'];
$services = getAllServices();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang khách hàng - QLDV</title>
    <link rel="stylesheet" href="../../css/main.css">
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f2f4f8;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: #004080;
            color: #fff;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h3 { margin: 0; }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
            font-weight: 500;
        }
        .navbar a:hover { text-decoration: underline; }
        .container { width: 90%; margin: 30px auto; }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 25px;
        }
        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 15px;
            margin-bottom: 25px;
        }
        .card h4 {
            color: #004080;
            margin-bottom: 8px;
        }
        .card p {
            color: #555;
            margin: 3px 0;
        }
        .card form { margin-top: 10px; }
        .card input[type=number] {
            width: 60px;
            padding: 4px;
        }
        .btn {
            padding: 6px 14px;
            border-radius: 4px;
            border: none;
            color: white;
            font-weight: 500;
            cursor: pointer;
        }
        .btn-book { background-color: #007bff; }
        .btn-book:hover { background-color: #0056b3; }
        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <!-- Thanh điều hướng -->
    <div class="navbar">
        <h3>🎬 Hệ thống đặt vé - QLDV</h3>
        <div>
            <a href="index.php">Trang chủ</a>
            <a href="history.php">Lịch sử đặt vé</a>
            <a href="../../handle/logout_process.php">Đăng xuất</a>
        </div>
    </div>

    <div class="container">
        <h2>Danh sách dịch vụ</h2>

        <!-- Thông báo -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (empty($services)): ?>
            <p style="text-align:center;">Chưa có dịch vụ nào được mở.</p>
        <?php else: ?>
            <?php foreach ($services as $service): 
                $schedules = getSchedulesByServiceId($service['service_id']);
            ?>
            <div class="card">
                <h4><?= htmlspecialchars($service['service_name']); ?></h4>
                <p><strong>Giá vé:</strong> <?= number_format($service['price'], 0, ',', '.'); ?> VNĐ</p>
                <p><strong>Mô tả:</strong> <?= htmlspecialchars($service['description'] ?? 'Không có'); ?></p>

                <?php if (!empty($schedules)): ?>
                    <form method="POST" action="../../handle/bookings_process.php">
                        <label>Chọn lịch chiếu:</label>
                        <select name="schedule_id" required>
                            <?php foreach ($schedules as $sch): ?>
                                <option value="<?= $sch['schedule_id']; ?>">
                                    <?= htmlspecialchars($sch['date']); ?> - <?= htmlspecialchars($sch['start_time']); ?> - <?= htmlspecialchars($sch['end_time']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label>Số người:</label>
                        <input type="number" name="num_people" value="1" min="1" required>

                        <input type="hidden" name="customer_id" value="<?= $customer_id; ?>">
                        <button type="submit" name="create" class="btn btn-book">Đặt vé</button>
                    </form>
                <?php else: ?>
                    <p style="color:gray;">Không có lịch chiếu nào khả dụng.</p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
