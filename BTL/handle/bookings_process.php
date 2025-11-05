<?php
session_start();
require_once __DIR__ . '/../functions/db_connection.php';
require_once __DIR__ . '/../functions/auth_functions.php';
require_once __DIR__ . '/../functions/bookings_functions.php';
require_once __DIR__ . '/../functions/schedules_functions.php';
require_once __DIR__ . '/../functions/services_functions.php';

// 🔐 Kiểm tra đăng nhập
checkLogin();

// ✅ Lấy thông tin người dùng hiện tại
$user_id      = $_SESSION['user_id'] ?? null;
$role_id      = $_SESSION['role_id'] ?? null;
$customer_id  = $_SESSION['customer_id'] ?? null; // ✅ thêm dòng này

// ✅ Chỉ cho phép khách hàng (role_id = 4)
if ($role_id != 4) {
    $_SESSION['error'] = 'Bạn không có quyền truy cập chức năng này!';
    header('Location: ../views/dashboard/index.php');
    exit();
}

// ✅ Hàm chuyển hướng sau xử lý
function redirect_list($message = null, $success = true) {
    if ($message) {
        if ($success) $_SESSION['success'] = $message;
        else $_SESSION['error'] = $message;
    }
    header('Location: ../views/customer/history.php');
    exit();
}

// =====================================
// 🟢 XỬ LÝ KHI GỬI FORM
// =====================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 🟢 TẠO ĐẶT VÉ
    if (isset($_POST['create'])) {
        $schedule_id = intval($_POST['schedule_id'] ?? 0);
        $num_people  = intval($_POST['num_people'] ?? 0);

        if (!$customer_id) redirect_list('Không tìm thấy thông tin khách hàng', false);
        if ($schedule_id <= 0) redirect_list('ID lịch không hợp lệ', false);
        if ($num_people <= 0) redirect_list('Số người phải lớn hơn 0', false);

        // ✅ Lấy thông tin lịch
        $schedule = getScheduleById($schedule_id);
        if (!$schedule) redirect_list('Không tìm thấy lịch', false);

        // ✅ Kiểm tra sức chứa còn lại
        $booked = getBookingCountForSchedule($schedule_id);
        $remaining = $schedule['capacity'] - $booked;
        if ($remaining < $num_people) redirect_list('Số lượng chỗ còn lại không đủ', false);

        // ✅ Lấy giá dịch vụ
        $service_price = getServicePriceById($schedule['service_id']);
        $total = $num_people * $service_price;

        // ✅ Ghi vào DB
        $ok = createBooking($customer_id, $schedule_id, $num_people, $total);
        if ($ok) redirect_list('🎟 Đặt vé thành công!');
        else redirect_list('Đặt vé thất bại', false);
    }

    // 🔴 HỦY ĐẶT VÉ
    if (isset($_POST['cancel'])) {
        $booking_ref = trim($_POST['booking_ref'] ?? '');
        if (!$booking_ref) redirect_list('Mã đặt vé không hợp lệ', false);

        $booking = getBookingByRef($booking_ref);
        if (!$booking) redirect_list('Không tìm thấy đặt vé', false);

        // ✅ Kiểm tra quyền sở hữu đặt vé
        if ($booking['customer_id'] != $customer_id)
            redirect_list('Bạn không có quyền hủy đặt vé này', false);

        if ($booking['status'] != 'pending')
            redirect_list('Không thể hủy đặt vé đã thanh toán hoặc đã hủy', false);

        $ok = updateBookingStatus($booking_ref, 'cancelled');
        if ($ok) redirect_list('Hủy đặt vé thành công!');
        else redirect_list('Hủy đặt vé thất bại', false);
    }
}

// Nếu không có POST hợp lệ
header('Location: ../views/customer/history.php');
exit();
?>
