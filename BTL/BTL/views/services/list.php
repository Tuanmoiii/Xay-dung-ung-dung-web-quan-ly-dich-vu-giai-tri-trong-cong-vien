<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
checkLogin();
require_once __DIR__ . '/../../functions/services_functions.php';

$services = getAllServices();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Danh sách dịch vụ</title>
	<link href="../../css/login.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            background-color: #0d6efd;
            min-height: 100vh;
            color: white;
            padding-top: 20px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            border-radius: 8px;
            margin: 5px 15px;
        }

        .sidebar a:hover,
        .sidebar .active {
            background-color: #0056b3;
        }

        .content {
            padding: 2rem;
        }

        .card {
            border-radius: 12px;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        footer {
            background: #e9ecef;
            padding: 10px 0;
            text-align: center;
            margin-top: 2rem;
        }
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
            <a href="list.php" class="active">🧾 Quản lý dịch vụ</a>
            <a href="../schedules/list.php">🗓️ Quản lý lịch chiếu</a>
            <a href="../customers/list.php">👤 Quản lý khách hàng</a>
            <a href="../bookings/history.php">🎟️ Quản lý đặt vé</a>
            <a href="../payments/list.php">💳 Quản lý thanh toán</a>

            <div class="mt-auto text-center">
                <a href="../../handle/logout_process.php" class="btn btn-light text-primary mt-3">Đăng xuất</a>
            </div>
        </div>
        <div class="container my-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Danh sách dịch vụ</h3>
                <a href="create.php" class="btn btn-primary">Thêm dịch vụ mới</a>
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
                        <th>Công viên</th>
                        <th>Tên dịch vụ</th>
                        <th>Mã</th>
                        <th>Thời lượng (phút)</th>
                        <th>Sức chứa</th>
                        <th>Giá</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($services)): foreach ($services as $s): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($s['service_id']); ?></td>
                            <td><?php echo htmlspecialchars($s['park_name'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($s['service_name']); ?></td>
                            <td><?php echo htmlspecialchars($s['code']); ?></td>
                            <td><?php echo htmlspecialchars($s['duration_minutes']); ?></td>
                            <td><?php echo htmlspecialchars($s['capacity']); ?></td>
                            <td><?php echo htmlspecialchars($s['price']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $s['service_id']; ?>" class="btn btn-sm btn-warning">Sửa</a>
                                <form action="../../handle/services_process.php" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa dịch vụ này?');">
                                    <input type="hidden" name="service_id" value="<?php echo $s['service_id']; ?>">
                                    <button type="submit" name="delete" class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="8" class="text-center">Chưa có dịch vụ nào.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
