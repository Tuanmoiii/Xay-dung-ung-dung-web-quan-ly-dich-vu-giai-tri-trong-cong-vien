<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
require_once __DIR__ . '/../../functions/bookings_functions.php';
require_once __DIR__ . '/../../functions/customers_functions.php';
require_once __DIR__ . '/../../functions/schedules_functions.php';
checkLogin();

// Validate schedule_id and customer_id
$schedule_id = intval($_GET['schedule_id'] ?? 0);
$customer_id = intval($_GET['customer_id'] ?? 0);
$num_people = intval($_GET['num_people'] ?? 1);

if ($schedule_id <= 0) {
    $_SESSION['error'] = 'ID lịch không hợp lệ';
    header('Location: ../schedules/list.php');
    exit();
}

$schedule = getScheduleById($schedule_id);
$customers = getAllCustomers();

if (!$schedule) {
    $_SESSION['error'] = 'Không tìm thấy lịch';
    header('Location: ../schedules/list.php');
    exit();
}

// Check if there's enough capacity
$booked = getBookingCountForSchedule($schedule_id);
$remaining = $schedule['capacity'] - $booked;
if ($remaining < $num_people) {
    $_SESSION['error'] = 'Số lượng chỗ còn lại không đủ';
    header('Location: ../schedules/list.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Đặt vé</title>
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
                <h3>Đặt vé mới</h3>
                <a href="../schedules/list.php" class="btn btn-secondary">Quay lại</a>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Thông tin lịch</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Dịch vụ:</strong> <?php echo htmlspecialchars($schedule['service_name']); ?></p>
                            <p><strong>Ngày:</strong> <?php echo htmlspecialchars($schedule['date']); ?></p>
                            <p><strong>Giờ:</strong> <?php echo htmlspecialchars($schedule['start_time']); ?> - <?php echo htmlspecialchars($schedule['end_time']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Giá vé:</strong> <?php echo number_format($schedule['price'], 0, ',', '.'); ?>đ/người</p>
                            <p><strong>Sức chứa:</strong> <?php echo htmlspecialchars($schedule['capacity']); ?> người</p>
                            <p><strong>Còn trống:</strong> <?php echo $remaining; ?> chỗ</p>
                        </div>
                    </div>
                </div>
            </div>

            <form action="../../handle/bookings_process.php" method="POST">
                <input type="hidden" name="schedule_id" value="<?php echo $schedule_id; ?>">

                <div class="mb-3">
                    <label class="form-label">Khách hàng</label>
                    <select name="customer_id" class="form-select" required>
                        <option value="">-- Chọn khách hàng --</option>
                        <?php foreach ($customers as $c): ?>
                            <option value="<?php echo $c['customer_id']; ?>" <?php echo $customer_id == $c['customer_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['full_name']); ?> (<?php echo htmlspecialchars($c['phone']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Số người</label>
                    <input type="number" name="num_people" class="form-control" min="1" max="<?php echo $remaining; ?>" value="<?php echo $num_people; ?>" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" name="create" class="btn btn-primary">Đặt vé</button>
                    <a href="../schedules/list.php" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
