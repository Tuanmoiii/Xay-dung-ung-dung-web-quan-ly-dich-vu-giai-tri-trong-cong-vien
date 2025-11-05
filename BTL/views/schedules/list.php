<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
checkLogin();
require_once __DIR__ . '/../../functions/services_functions.php';
require_once __DIR__ . '/../../functions/schedules_functions.php';

$services = getAllServices();

$service_id = intval($_GET['service_id'] ?? 0);
$schedules = [];
if ($service_id > 0) {
	$schedules = getSchedulesByService($service_id);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Danh sách lịch chiếu</title>
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
			<a href="list.php" class="active">🗓️ Quản lý lịch chiếu</a>
			<a href="../customers/list.php">👤 Quản lý khách hàng</a>
			<a href="../bookings/history.php">🎟️ Quản lý đặt vé</a>
			<a href="../payments/list.php">💳 Quản lý thanh toán</a>
			<div class="mt-auto text-center">
				<a href="../../handle/logout_process.php" class="btn btn-light text-primary mt-3">Đăng xuất</a>
			</div>
		</div>
		<div class="container my-5">
			<div class="d-flex justify-content-between align-items-center mb-4">
				<h3>Danh sách lịch chiếu</h3>
				<div>
					<a href="create.php" class="btn btn-primary">Thêm lịch mới</a>
				</div>
			</div>

			<?php if (isset($_SESSION['error'])): ?>
				<div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
			<?php endif; ?>
			<?php if (isset($_SESSION['success'])): ?>
				<div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
			<?php endif; ?>

			<div class="mb-3">
				<form method="get" class="row g-2 align-items-center">
					<div class="col-auto">
						<label class="visually-hidden">Dịch vụ</label>
						<select name="service_id" class="form-select">
							<option value="0">-- Chọn dịch vụ --</option>
							<?php foreach ($services as $sv): ?>
								<option value="<?php echo $sv['service_id']; ?>" <?php echo $service_id == $sv['service_id'] ? 'selected' : ''; ?>>
									<?php echo htmlspecialchars($sv['service_name']); ?> (<?php echo htmlspecialchars($sv['code']); ?>)
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-auto">
						<button class="btn btn-secondary">Xem</button>
					</div>
				</form>
			</div>

			<div class="table-responsive">
				<table class="table table-striped table-bordered">
					<thead class="table-light">
					<tr>
						<th>#</th>
						<th>Ngày</th>
						<th>Giờ bắt đầu</th>
						<th>Giờ kết thúc</th>
						<th>Sức chứa</th>
						<th>Trạng thái</th>
						<th>Hành động</th>
					</tr>
					</thead>
					<tbody>
					<?php if ($service_id <= 0): ?>
						<tr><td colspan="7" class="text-center">Vui lòng chọn một dịch vụ để xem lịch.</td></tr>
					<?php else: if (!empty($schedules)): foreach ($schedules as $i => $sch): ?>
						<tr>
							<td><?php echo htmlspecialchars($sch['schedule_id']); ?></td>
							<td><?php echo htmlspecialchars($sch['date']); ?></td>
							<td><?php echo htmlspecialchars($sch['start_time']); ?></td>
							<td><?php echo htmlspecialchars($sch['end_time']); ?></td>
							<td><?php echo htmlspecialchars($sch['capacity']); ?></td>
							<td><?php echo htmlspecialchars($sch['status'] ?? 'active'); ?></td>
							<td>
								<a href="edit.php?id=<?php echo $sch['schedule_id']; ?>&service_id=<?php echo $service_id; ?>" class="btn btn-sm btn-warning">Sửa</a>
								<form action="../../handle/schedules_process.php" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa lịch này?');">
									<input type="hidden" name="schedule_id" value="<?php echo $sch['schedule_id']; ?>">
									<input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
									<button type="submit" name="delete" class="btn btn-sm btn-danger">Xóa</button>
								</form>
							</td>
						</tr>
					<?php endforeach; else: ?>
						<tr><td colspan="7" class="text-center">Chưa có lịch nào cho dịch vụ này.</td></tr>
					<?php endif; endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>
</html>

