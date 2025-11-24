<?php
session_start();
require_once __DIR__ . '/../functions/payments_functions.php';
require_once __DIR__ . '/../functions/bookings_functions.php';
require_once __DIR__ . '/../functions/auth_functions.php';
checkLogin();

function redirect_list($message = null, $success = true) {
    if ($message) {
        if ($success) $_SESSION['success'] = $message;
        else $_SESSION['error'] = $message;
    }
    header('Location: ../views/payments/list.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a payment (simulated success)
    if (isset($_POST['create'])) {
        $booking_ref = trim($_POST['booking_ref'] ?? '');
        $method = trim($_POST['method'] ?? 'cash');

        if ($booking_ref === '') redirect_list('Mã đặt vé không hợp lệ', false);

        $booking = getBookingByRef($booking_ref);
        if (!$booking) redirect_list('Không tìm thấy đặt vé', false);

        // Use booking_id to record payment
        $booking_id = $booking['booking_id'] ?? null;
        if (!$booking_id) redirect_list('ID đặt vé không hợp lệ', false);

        $amount = floatval($booking['total_amount'] ?? 0);
        $ok = addPayment($booking_id, $amount, $method);
        if ($ok) {
            // mark booking as paid
            updateBooking($booking_ref, 'paid');
            redirect_list('Thanh toán thành công');
        }
        redirect_list('Thanh toán thất bại', false);
    }

    // Optional: refund/delete payment
    if (isset($_POST['refund'])) {
        $payment_id = intval($_POST['payment_id'] ?? 0);
        // For now, simply mark booking as cancelled and remove payment record if functions exist
        // Implement when payment deletion is supported in functions/payments_functions.php
        redirect_list('Chức năng refund chưa được triển khai', false);
    }
}

header('Location: ../views/payments/list.php');
exit();

?>
