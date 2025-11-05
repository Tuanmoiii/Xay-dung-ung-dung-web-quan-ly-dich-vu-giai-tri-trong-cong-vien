<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
checkLogin();
require_once __DIR__ . '/../../functions/services_functions.php';
require_once __DIR__ . '/../../functions/db_connection.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
	$_SESSION['error'] = 'ID dịch vụ không hợp lệ';
	header('Location: list.php');
	exit();
}

$service = getServiceById($id);
if (!$service) {
	$_SESSION['error'] = 'Không tìm thấy dịch vụ';
	header('Location: list.php');
	exit();
}

$conn = getDbConnection();
$parks = [];
$res = mysqli_query($conn, "SELECT park_id, park_name FROM parks ORDER BY park_name ASC");
if ($res) while ($r = mysqli_fetch_assoc($res)) $parks[] = $r;
?>
<!doctype html>
<html lang="vi">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Chỉnh sửa dịch vụ</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../css/login.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
	<h3>Chỉnh sửa dịch vụ</h3>
	<form action="../../handle/services_process.php" method="POST" class="needs-validation" novalidate>
		<input type="hidden" name="service_id" value="<?php echo $service['service_id']; ?>">

		<div class="mb-3">
			<label for="park_id" class="form-label">Công viên</label>
			<select name="park_id" id="park_id" class="form-select" disabled>
				<?php foreach ($parks as $p): ?>
					<option value="<?php echo $p['park_id']; ?>" <?php if ($p['park_id']==$service['park_id']) echo 'selected'; ?>><?php echo htmlspecialchars($p['park_name']); ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="mb-3">
			<label class="form-label">Tên dịch vụ</label>
			<input type="text" name="service_name" class="form-control" value="<?php echo htmlspecialchars($service['service_name']); ?>" required>
		</div>

		<div class="mb-3">
			<label class="form-label">Mô tả</label>
			<textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($service['description']); ?></textarea>
		</div>

		<div class="row">
			<div class="col-md-4 mb-3">
				<label class="form-label">Thời lượng (phút)</label>
				<input type="number" name="duration" class="form-control" value="<?php echo htmlspecialchars($service['duration_minutes']); ?>">
			</div>
			<div class="col-md-4 mb-3">
				<label class="form-label">Sức chứa</label>
				<input type="number" name="capacity" class="form-control" value="<?php echo htmlspecialchars($service['capacity']); ?>">
			</div>
			<div class="col-md-4 mb-3">
				<label class="form-label">Giá</label>
				<input type="number" step="0.01" name="price" class="form-control" value="<?php echo htmlspecialchars($service['price']); ?>">
			</div>
		</div>

		<div class="form-check mb-3">
			<input class="form-check-input" type="checkbox" id="active" name="active" <?php if ($service['active']) echo 'checked'; ?>>
			<label class="form-check-label" for="active">Kích hoạt</label>
		</div>

		<div class="d-grid gap-2">
			<button type="submit" name="update" class="btn btn-primary">Lưu thay đổi</button>
			<a href="list.php" class="btn btn-secondary">Hủy</a>
		</div>
	</form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
	(function () {
		'use strict'
		var forms = document.querySelectorAll('.needs-validation')
		Array.prototype.slice.call(forms)
			.forEach(function (form) {
				form.addEventListener('submit', function (event) {
					if (!form.checkValidity()) {
						event.preventDefault()
						event.stopPropagation()
					}
					form.classList.add('was-validated')
				}, false)
			})
	})()
</script>
</body>
</html>
