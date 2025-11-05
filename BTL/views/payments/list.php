<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
require_once __DIR__ . '/../../functions/payments_functions.php';
checkLogin();

$payments = getPayments();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
   <title>Danh sách thanh toán</title>
    <link href="../../css/login.css" rel="stylesheet"> 
	<link href="../../css/login.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		body { background-color: #f8f9fa; }
		.sidebar { background-color: #0d6efd; min-height: 100vh; color: white; padding-top: 20px; }
		.sidebar a { color: white; text-decoration: none; padding: 12px 20px; display: block; border-radius: 8px; margin: 5px 15px; }
		.sidebar a:hover, .sidebar .active { background-color: #0056b3; }
		.content { padding: 2rem; }
		.card { border-radius: 12px; transition: 0.3s; }
		footer { background: #e9ecef; padding: 10px 0; text-align: center; margin-top: 2rem; }
	</style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <div class="sidebar">
            <div class="text-center mb-4">
                <img src="../../images/fitdnu_logo.png" class="img-fluid mb-2" style="max-width: 80px;" alt="Logo">
                <h5>QLDV - FITDNU</h5>
            </div>

            <a href="../dashboard/index.php">🏠 Trang chủ</a>
            <a href="../services/list.php">🧾 Quản lý dịch vụ</a>
            <a href="../schedules/list.php">🗓️ Quản lý lịch chiếu</a>
            <a href="../customers/list.php">👤 Quản lý khách hàng</a>
            <a href="../bookings/history.php">🎟️ Quản lý đặt vé</a>
            <a href="list.php" class="active">💳 Quản lý thanh toán</a>

            <div class="mt-auto text-center">
                <a href="../../handle/logout_process.php" class="btn btn-light text-primary mt-3">Đăng xuất</a>
            </div>
        </div>

        <div class="container my-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Danh sách thanh toán</h3>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Mã đặt</th>
                            <th>Khách hàng</th>
                            <th>Số tiền</th>
                            <th>Phương thức</th>
                            <th>Thời gian</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($payments)): foreach ($payments as $p): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['payment_id'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($p['booking_ref'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($p['full_name'] ?? ''); ?></td>
                            <td><?php echo number_format($p['amount'] ?? 0, 0, ',', '.'); ?>đ</td>
                            <td><?php echo htmlspecialchars($p['method'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($p['paid_at'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="6" class="text-center">Chưa có thanh toán nào.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>