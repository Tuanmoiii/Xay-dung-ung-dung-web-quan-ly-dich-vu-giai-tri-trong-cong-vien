<?php
session_start();
require_once __DIR__ . '/../functions/auth_functions.php';
require_once __DIR__ . '/../functions/bookings_functions.php';
checkLogin();

function redirect_list($message = null, $success = true) {
    if ($message) {
        if ($success) $_SESSION['success'] = $message;
        else $_SESSION['error'] = $message;
    }
    header('Location: ../views/bookings/history.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create booking
    if (isset($_POST['create'])) {
        $schedule_id = intval($_POST['schedule_id'] ?? 0);
        $customer_id = intval($_POST['customer_id'] ?? 0);
        $num_people = intval($_POST['num_people'] ?? 0);

        if ($schedule_id <= 0) redirect_list('ID lịch không hợp lệ', false);
        if ($customer_id <= 0) redirect_list('Vui lòng chọn khách hàng', false);
        if ($num_people <= 0) redirect_list('Số người phải lớn hơn 0', false);

        // Get schedule details to calculate total
        $schedule = getScheduleById($schedule_id);
        if (!$schedule) redirect_list('Không tìm thấy lịch', false);

        // Check capacity
        $booked = getBookingCountForSchedule($schedule_id);
        $remaining = $schedule['capacity'] - $booked;
        if ($remaining < $num_people) redirect_list('Số lượng chỗ còn lại không đủ', false);

        // Calculate total
        $total = $num_people * $schedule['price'];

        // Create booking
        $ref = createBooking($customer_id, $schedule_id, $num_people, $total);
        if ($ref) redirect_list("Đặt vé thành công. Mã đặt: $ref");
        redirect_list('Đặt vé thất bại', false);
    }

    // Cancel booking
    if (isset($_POST['cancel'])) {
        $ref = trim($_POST['booking_ref'] ?? '');
        if (!$ref) redirect_list('Mã đặt vé không hợp lệ', false);

        $booking = getBookingByRef($ref);
        if (!$booking) redirect_list('Không tìm thấy đặt vé', false);
        if ($booking['status'] != 'pending') redirect_list('Không thể hủy đặt vé này', false);

        $ok = updateBooking($ref, 'cancelled');
        if ($ok) redirect_list('Hủy đặt vé thành công');
        redirect_list('Hủy đặt vé thất bại', false);
    }
}

header('Location: ../views/bookings/history.php');
exit();
?>
