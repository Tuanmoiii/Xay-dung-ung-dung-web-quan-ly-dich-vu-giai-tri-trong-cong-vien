<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
require_once __DIR__ . '/../../functions/bookings_functions.php';
require_once __DIR__ . '/../../functions/schedules_functions.php';
require_once __DIR__ . '/../../functions/services_functions.php';
checkLogin();

// ✅ Chỉ cho phép khách hàng truy cập
if ($_SESSION['role_id'] != 4) {
    $_SESSION['error'] = 'Bạn không có quyền truy cập trang này!';
    header('Location: ../dashboard/index.php');
    exit();
}

$customer_id = $_SESSION['user_id'];
$bookings = getBookingsByCustomer($customer_id);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử đặt vé - QLDV</title>
    <link rel="stylesheet" href="../../css/main.css">
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f6f8fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 25px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        th, td {
            padding: 10px 8px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #0066cc;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-weight: 500;
        }
        .btn-cancel {
            background-color: #dc3545;
        }
        .btn-disabled {
            background-color: #aaa;
            cursor: not-allowed;
        }
        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
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
    <div class="container">
        <h2>🎟 Lịch sử đặt vé của bạn</h2>

        <!-- Thông báo -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (empty($bookings)): ?>
            <p style="text-align:center; color:#777;">Bạn chưa có đặt vé nào.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Mã đặt vé</th>
                        <th>Dịch vụ</th>
                        <th>Ngày chiếu</th>
                        <th>Số người</th>
                        <th>Tổng tiền (VNĐ)</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $b): 
                        $schedule = getScheduleById($b['schedule_id']);
                        $service = getServiceById($schedule['service_id']);
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($b['booking_ref']); ?></td>
                        <td><?= htmlspecialchars($service['service_name']); ?></td>
                        <td><?= htmlspecialchars($schedule['date']); ?></td>
                        <td><?= intval($b['num_people']); ?></td>
                        <td><?= number_format($b['total'], 0, ',', '.'); ?></td>
                        <td>
                            <?php if ($b['status'] === 'pending'): ?>
                                <span style="color: orange;">Chờ xử lý</span>
                            <?php elseif ($b['status'] === 'paid'): ?>
                                <span style="color: green;">Đã thanh toán</span>
                            <?php else: ?>
                                <span style="color: red;">Đã hủy</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($b['status'] === 'pending'): ?>
                                <form method="POST" action="../../handle/bookings_process.php" style="display:inline;">
                                    <input type="hidden" name="booking_ref" value="<?= htmlspecialchars($b['booking_ref']); ?>">
                                    <button type="submit" name="cancel" class="btn btn-cancel">Hủy</button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-disabled" disabled>Không khả dụng</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div style="margin-top:20px; text-align:center;">
            <a href="../customer/index.php" class="btn" style="background-color:#007bff;">⬅ Quay lại trang chính</a>
        </div>
    </div>
</body>
</html>
