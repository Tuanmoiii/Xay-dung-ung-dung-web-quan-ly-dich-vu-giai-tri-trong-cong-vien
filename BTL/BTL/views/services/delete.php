<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
checkLogin();
require_once __DIR__ . '/../../functions/services_functions.php';

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
?>
<!doctype html>
<html lang="vi">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Xóa dịch vụ</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../css/login.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
	<h3>Xóa dịch vụ</h3>
	<div class="alert alert-warning">Bạn có chắc muốn xóa dịch vụ <strong><?php echo htmlspecialchars($service['service_name']); ?></strong> ?</div>

	<form action="../../handle/services_process.php" method="POST">
		<input type="hidden" name="service_id" value="<?php echo $service['service_id']; ?>">
		<div class="d-flex gap-2">
			<button type="submit" name="delete" class="btn btn-danger">Xóa</button>
			<a href="list.php" class="btn btn-secondary">Hủy</a>
		</div>
	</form>
</div>
</body>
</html>
