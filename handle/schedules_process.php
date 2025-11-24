<?php
session_start();
require_once __DIR__ . '/../functions/schedules_functions.php';
require_once __DIR__ . '/../functions/auth_functions.php';
require_once __DIR__ . '/../functions/db_connection.php';
checkLogin();

function redirect_list($service_id = null, $message = null, $success = true) {
    if ($message) {
        if ($success) $_SESSION['success'] = $message;
        else $_SESSION['error'] = $message;
    }
    $loc = '../views/schedules/list.php';
    if ($service_id) $loc .= '?service_id=' . intval($service_id);
    header('Location: ' . $loc);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create schedule
    if (isset($_POST['create'])) {
        $service_id = intval($_POST['service_id'] ?? 0);
        $date = trim($_POST['date'] ?? '');
        $start = trim($_POST['start_time'] ?? '');
        $end = trim($_POST['end_time'] ?? '');
        $capacity = intval($_POST['capacity'] ?? 0);

        if ($service_id <= 0) redirect_list(null, 'Dịch vụ không hợp lệ', false);
        if ($date === '' || $start === '' || $end === '') redirect_list($service_id, 'Vui lòng nhập đầy đủ thông tin', false);

        $ok = addSchedule($service_id, $date, $start, $end, $capacity);
        if ($ok) redirect_list($service_id, 'Tạo lịch thành công');
        redirect_list($service_id, 'Tạo lịch thất bại', false);
    }

    // Update schedule
    if (isset($_POST['update'])) {
        $id = intval($_POST['schedule_id'] ?? 0);
        $service_id = intval($_POST['service_id'] ?? 0);
        $date = trim($_POST['date'] ?? '');
        $start = trim($_POST['start_time'] ?? '');
        $end = trim($_POST['end_time'] ?? '');
        $capacity = intval($_POST['capacity'] ?? 0);
        $status = trim($_POST['status'] ?? 'active');

        if ($id <= 0) redirect_list($service_id, 'ID lịch không hợp lệ', false);
        if ($date === '' || $start === '' || $end === '') redirect_list($service_id, 'Vui lòng nhập đầy đủ thông tin', false);

        $ok = updateSchedule($id, $date, $start, $end, $capacity, $status);
        if ($ok) redirect_list($service_id, 'Cập nhật lịch thành công');
        redirect_list($service_id, 'Cập nhật lịch thất bại', false);
    }

    // Delete schedule
    if (isset($_POST['delete'])) {
        $id = intval($_POST['schedule_id'] ?? 0);
        $service_id = intval($_POST['service_id'] ?? 0);
        if ($id <= 0) redirect_list($service_id, 'ID lịch không hợp lệ', false);
        $ok = deleteSchedule($id);
        if ($ok) redirect_list($service_id, 'Xóa lịch thành công');
        redirect_list($service_id, 'Xóa lịch thất bại', false);
    }
}

// fallback
header('Location: ../views/schedules/list.php');
exit();

?>
