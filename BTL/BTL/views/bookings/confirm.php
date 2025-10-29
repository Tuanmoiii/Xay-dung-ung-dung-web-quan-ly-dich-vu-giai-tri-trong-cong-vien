<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
require_once __DIR__ . '/../../functions/bookings_functions.php';
checkLogin();

$ref = trim($_GET['ref'] ?? '');
if (!$ref) {
    $_SESSION['error'] = 'Mã đặt vé không hợp lệ';
    header('Location: history.php');
    exit();
}

$booking = getBookingByRef($ref);
if (!$booking) {
    $_SESSION['error'] = 'Không tìm thấy đặt vé';
    header('Location: history.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Chi tiết đặt vé</title>
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
                <h3>Chi tiết đặt vé</h3>
                <a href="history.php" class="btn btn-secondary">Quay lại</a>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="card-title">Thông tin đặt vé</h5>
                            <p><strong>Mã đặt vé:</strong> <?php echo htmlspecialchars($booking['booking_ref']); ?></p>
                            <p><strong>Trạng thái:</strong> 
                                <span class="badge <?php echo $booking['status'] == 'paid' ? 'bg-success' : ($booking['status'] == 'pending' ? 'bg-warning' : 'bg-danger'); ?>">
                                    <?php echo $booking['status'] == 'paid' ? 'Đã thanh toán' : ($booking['status'] == 'pending' ? 'Chờ thanh toán' : 'Đã hủy'); ?>
                                </span>
                            </p>
                            <p><strong>Khách hàng:</strong> <?php echo htmlspecialchars($booking['full_name']); ?></p>
                            <p><strong>Số người:</strong> <?php echo htmlspecialchars($booking['num_people']); ?></p>
                            <p><strong>Tổng tiền:</strong> <?php echo number_format($booking['total_amount'], 0, ',', '.'); ?>đ</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title">Thông tin dịch vụ</h5>
                            <p><strong>Dịch vụ:</strong> <?php echo htmlspecialchars($booking['service_name']); ?></p>
                            <p><strong>Ngày:</strong> <?php echo htmlspecialchars($booking['date']); ?></p>
                            <p><strong>Giờ bắt đầu:</strong> <?php echo htmlspecialchars($booking['start_time']); ?></p>
                            <p><strong>Giờ kết thúc:</strong> <?php echo htmlspecialchars($booking['end_time']); ?></p>
                            <p><strong>Giá vé:</strong> <?php echo number_format($booking['price'], 0, ',', '.'); ?>đ/người</p>
                        </div>
                    </div>

                    <?php if ($booking['status'] == 'pending'): ?>
                        <div class="d-flex gap-2">
                            <form action="../../handle/bookings_process.php" method="POST" class="d-inline">
                                <input type="hidden" name="booking_ref" value="<?php echo $booking['booking_ref']; ?>">
                                <button type="submit" name="cancel" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn hủy đặt vé này?')">Hủy đặt vé</button>
                            </form>
                            <a href="../payments/create.php?ref=<?php echo $booking['booking_ref']; ?>" class="btn btn-success">Thanh toán</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
