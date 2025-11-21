<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
checkLogin();
require_once __DIR__ . '/../../functions/services_functions.php';

$services = getAllServices();
$service_id = intval($_GET['service_id'] ?? 0);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Thêm lịch</title>
	<link href="../../css/login.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div class="container my-5">
		<div class="d-flex justify-content-between align-items-center mb-4">
			<h3>Thêm lịch mới</h3>
			<a href="list.php" class="btn btn-secondary">Quay lại</a>
		</div>

		<?php if (isset($_SESSION['error'])): ?>
			<div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
		<?php endif; ?>
		<?php if (isset($_SESSION['success'])): ?>
			<div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
		<?php endif; ?>

		<form action="../../handle/schedules_process.php" method="POST">
			<div class="mb-3">
				<label class="form-label">Dịch vụ</label>
				<select name="service_id" class="form-select">
					<option value="0">-- Chọn dịch vụ --</option>
					<?php foreach ($services as $sv): ?>
						<option value="<?php echo $sv['service_id']; ?>" <?php echo $service_id == $sv['service_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($sv['service_name']); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="row">
				<div class="col-md-4 mb-3">
					<label class="form-label">Ngày</label>
					<input type="date" name="date" class="form-control" required>
				</div>
				<div class="col-md-4 mb-3">
					<label class="form-label">Giờ bắt đầu</label>
					<input type="time" name="start_time" class="form-control" required>
				</div>
				<div class="col-md-4 mb-3">
					<label class="form-label">Giờ kết thúc</label>
					<input type="time" name="end_time" class="form-control" required>
				</div>
			</div>

			<div class="mb-3">
				<label class="form-label">Sức chứa</label>
				<input type="number" name="capacity" class="form-control" value="0" min="0">
			</div>

			<div class="d-flex gap-2">
				<button type="submit" name="create" class="btn btn-primary">Tạo</button>
				<a href="list.php" class="btn btn-secondary">Hủy</a>
			</div>
		</form>
	</div>
</body>
</html>

