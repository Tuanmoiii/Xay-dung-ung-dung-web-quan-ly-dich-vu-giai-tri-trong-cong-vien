<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
checkLogin();
require_once __DIR__ . '/../../functions/customers_functions.php';

$customers = getAllCustomers();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Danh sách khách hàng</title>
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
			<a href="list.php" class="active">👤 Quản lý khách hàng</a>
			<a href="../bookings/history.php">🎟️ Quản lý đặt vé</a>
			<a href="../payments/list.php">💳 Quản lý thanh toán</a>
			<div class="mt-auto text-center">
				<a href="../../handle/logout_process.php" class="btn btn-light text-primary mt-3">Đăng xuất</a>
			</div>
		</div>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Danh sách khách hàng</h3>
            <a href="create.php" class="btn btn-primary">Thêm khách hàng</a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Họ và tên</th>
                        <th>Email</th>
                        <th>Điện thoại</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($customers)): foreach ($customers as $c): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($c['customer_id']); ?></td>
                            <td><?php echo htmlspecialchars($c['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($c['email']); ?></td>
                            <td><?php echo htmlspecialchars($c['phone']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $c['customer_id']; ?>" class="btn btn-sm btn-warning">Sửa</a>
                                <form action="../../handle/customers_process.php" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa khách hàng này?');">
                                    <input type="hidden" name="customer_id" value="<?php echo $c['customer_id']; ?>">
                                    <button type="submit" name="delete" class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5" class="text-center">Chưa có khách hàng nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
