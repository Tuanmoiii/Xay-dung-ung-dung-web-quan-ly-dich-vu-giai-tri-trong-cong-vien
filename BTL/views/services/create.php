<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
checkLogin();
require_once __DIR__ . '/../../functions/db_connection.php';

$conn = getDbConnection();
$parks = [];
$res = mysqli_query($conn, "SELECT park_id, park_name FROM parks ORDER BY park_name ASC");
if ($res) {
	while ($r = mysqli_fetch_assoc($res)) $parks[] = $r;
}
?>
<!doctype html>
<html lang="vi">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Thêm dịch vụ</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../css/login.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
	<h3>Thêm dịch vụ mới</h3>
	<form action="../../handle/services_process.php" method="POST" class="needs-validation" novalidate>
		<div class="mb-3">
			<label for="park_id" class="form-label">Công viên (park_id)</label>
			<select name="park_id" id="park_id" class="form-select" required>
				<option value="">-- Chọn công viên --</option>
				<?php foreach ($parks as $p): ?>
					<option value="<?php echo $p['park_id']; ?>"><?php echo htmlspecialchars($p['park_name']); ?></option>
				<?php endforeach; ?>
			</select>
			<div class="invalid-feedback">Vui lòng chọn công viên</div>
		</div>

		<div class="mb-3">
			<label class="form-label">Tên dịch vụ</label>
			<input type="text" name="service_name" class="form-control" required>
			<div class="invalid-feedback">Vui lòng nhập tên dịch vụ</div>
		</div>

		<div class="mb-3">
			<label class="form-label">Mã (code)</label>
			<input type="text" name="code" class="form-control">
		</div>

		<div class="mb-3">
			<label class="form-label">Mô tả</label>
			<textarea name="description" class="form-control" rows="3"></textarea>
		</div>

		<div class="row">
			<div class="col-md-4 mb-3">
				<label class="form-label">Thời lượng (phút)</label>
				<input type="number" name="duration" class="form-control" value="30">
			</div>
			<div class="col-md-4 mb-3">
				<label class="form-label">Sức chứa</label>
				<input type="number" name="capacity" class="form-control" value="10">
			</div>
			<div class="col-md-4 mb-3">
				<label class="form-label">Giá</label>
				<input type="number" step="0.01" name="price" class="form-control" value="0">
			</div>
		</div>

		<div class="d-grid gap-2">
			<button type="submit" name="create" class="btn btn-primary">Tạo dịch vụ</button>
			<a href="list.php" class="btn btn-secondary">Hủy</a>
		</div>
	</form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
	// Bootstrap validation
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
