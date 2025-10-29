<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
require_once __DIR__ . '/../../functions/bookings_functions.php';
checkLogin();

$bookings = getAllBookings();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Lịch sử đặt vé</title>
    <link href="../../css/login.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { background-color: #0d6efd; min-height: 100vh; color: white; padding-top: 20px; }
        .sidebar a { color: white; text-decoration: none; padding: 12px 20px; display: block; border-radius: 8px; margin: 5px 15px; }
        .sidebar a:hover, .sidebar .active { background-color: #0056b3; }
        .content { padding: 2rem; }
        .card { border-radius: 12px; transition: 0.3s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15); }
        footer { background: #e9ecef; padding: 10px 0; text-align: center; margin-top: 2rem; }
    </style>
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
            <a href="history.php" class="active">🎟️ Quản lý đặt vé</a>
            <a href="../payments/list.php">💳 Quản lý thanh toán</a>

            <div class="mt-auto text-center">
                <a href="../../handle/logout_process.php" class="btn btn-light text-primary mt-3">Đăng xuất</a>
            </div>
        </div>
        
        <div class="container my-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Lịch sử đặt vé</h3>
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
                            <th>Mã đặt</th>
                            <th>Khách hàng</th>
                            <th>Dịch vụ</th>
                            <th>Ngày</th>
                            <th>Giờ</th>
                            <th>Số người</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($bookings)): foreach ($bookings as $b): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($b['booking_ref']); ?></td>
                            <td><?php echo htmlspecialchars($b['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($b['service_name']); ?></td>
                            <td><?php echo htmlspecialchars($b['date']); ?></td>
                            <td><?php echo htmlspecialchars($b['start_time']); ?> - <?php echo htmlspecialchars($b['end_time']); ?></td>
                            <td><?php echo htmlspecialchars($b['num_people']); ?></td>
                            <td><?php echo number_format($b['total_amount'], 0, ',', '.'); ?>đ</td>
                            <td>
                                <span class="badge <?php echo $b['status'] == 'paid' ? 'bg-success' : ($b['status'] == 'pending' ? 'bg-warning' : 'bg-danger'); ?>">
                                    <?php echo $b['status'] == 'paid' ? 'Đã thanh toán' : ($b['status'] == 'pending' ? 'Chờ thanh toán' : 'Đã hủy'); ?>
                                </span>
                            </td>
                            <td>
                                <a href="confirm.php?ref=<?php echo $b['booking_ref']; ?>" class="btn btn-sm btn-info">Chi tiết</a>
                                <?php if ($b['status'] == 'pending'): ?>
                                    <form action="../../handle/bookings_process.php" method="POST" style="display:inline-block">
                                        <input type="hidden" name="booking_ref" value="<?php echo $b['booking_ref']; ?>">
                                        <button type="submit" name="cancel" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn hủy đặt vé này?')">Hủy</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="9" class="text-center">Chưa có đặt vé nào.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
