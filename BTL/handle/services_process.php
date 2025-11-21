<?php
session_start();
require_once __DIR__ . '/../functions/services_functions.php';

// Helper redirect
function redirect_list($message = null, $success = true) {
	if ($message) {
		if ($success) $_SESSION['success'] = $message;
		else $_SESSION['error'] = $message;
	}
	header('Location: ../views/services/list.php');
	exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Create
	if (isset($_POST['create'])) {
		$park_id = intval($_POST['park_id'] ?? 0);
		$service_name = trim($_POST['service_name'] ?? '');
		$code = trim($_POST['code'] ?? '');
		$description = trim($_POST['description'] ?? '');
		$duration = intval($_POST['duration'] ?? 0);
		$capacity = intval($_POST['capacity'] ?? 0);
		$price = floatval($_POST['price'] ?? 0);

		if ($service_name === '') redirect_list('Tên dịch vụ không được để trống', false);

		$ok = addService($park_id, $service_name, $code, $description, $duration, $capacity, $price);
		if ($ok) redirect_list('Tạo dịch vụ thành công');
		redirect_list('Tạo dịch vụ thất bại', false);
	}

	// Update
	if (isset($_POST['update'])) {
		$id = intval($_POST['service_id'] ?? 0);
		$service_name = trim($_POST['service_name'] ?? '');
		$description = trim($_POST['description'] ?? '');
		$duration = intval($_POST['duration'] ?? 0);
		$capacity = intval($_POST['capacity'] ?? 0);
		$price = floatval($_POST['price'] ?? 0);
		$active = isset($_POST['active']) ? 1 : 0;

		if ($id <= 0) redirect_list('ID dịch vụ không hợp lệ', false);

		$ok = updateService($id, $service_name, $description, $duration, $capacity, $price, $active);
		if ($ok) redirect_list('Cập nhật dịch vụ thành công');
		redirect_list('Cập nhật dịch vụ thất bại', false);
	}

	// Delete
	if (isset($_POST['delete'])) {
		$id = intval($_POST['service_id'] ?? 0);
		if ($id <= 0) redirect_list('ID dịch vụ không hợp lệ', false);
		$ok = deleteService($id);
		if ($ok) redirect_list('Xóa dịch vụ thành công');
		redirect_list('Xóa dịch vụ thất bại', false);
	}
}

// If reached here, go back to list
header('Location: ../views/services/list.php');
exit();

