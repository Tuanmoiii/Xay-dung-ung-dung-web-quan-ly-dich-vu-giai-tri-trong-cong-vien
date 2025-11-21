<?php
// ===========================================
// bookings_functions.php
// Quản lý đặt vé
// ===========================================
require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/services_functions.php';
require_once __DIR__ . '/customers_functions.php';



function createBooking($customer_id, $schedule_id, $num_people, $total_amount)
{
    $conn = getDbConnection();
    $ref = 'BK' . strtoupper(uniqid());
    // Set initial status to 'pending' so new bookings are tracked consistently
    $sql = "INSERT INTO bookings (booking_ref, customer_id, schedule_id, num_people, total_amount, status)
            VALUES (?, ?, ?, ?, ?, 'pending')";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "siiid", $ref, $customer_id, $schedule_id, $num_people, $total_amount);
    $ok = mysqli_stmt_execute($stmt);
    if ($ok) return $ref;
    return false;
}

function getBookingByRef($ref)
{
    $conn = getDbConnection();
    $sql = "SELECT b.*, c.full_name, s.date, s.start_time, s.end_time, sv.service_name,
                   sv.price, s.capacity
            FROM bookings b
            JOIN customers c ON b.customer_id = c.customer_id
            JOIN schedules s ON b.schedule_id = s.schedule_id
            JOIN services sv ON s.service_id = sv.service_id
            WHERE b.booking_ref = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $ref);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

/**
 * Lấy danh sách đặt vé
 */
function getAllBookings()
{
    $conn = getDbConnection();
    $sql = "SELECT b.*, c.full_name, s.date, s.start_time, s.end_time, sv.service_name
            FROM bookings b
            JOIN customers c ON b.customer_id = c.customer_id
            JOIN schedules s ON b.schedule_id = s.schedule_id
            JOIN services sv ON s.service_id = sv.service_id
            ORDER BY b.created_at DESC";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Cập nhật trạng thái đặt vé
 */
function updateBooking($ref, $status)
{
    $conn = getDbConnection();
    $sql = "UPDATE bookings SET status = ? WHERE booking_ref = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $status, $ref);
    return mysqli_stmt_execute($stmt);
}

/**
 * Hủy đặt vé
 */
function deleteBooking($ref)
{
    $conn = getDbConnection();
    $sql = "DELETE FROM bookings WHERE booking_ref = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $ref);
    return mysqli_stmt_execute($stmt);
}

/**
 * Đếm số vé đã đặt cho một lịch
 */
function getBookingCountForSchedule($schedule_id)
{
    $conn = getDbConnection();
    // Exclude cancelled bookings when counting capacity usage
    $sql = "SELECT COALESCE(SUM(num_people), 0) as total FROM bookings WHERE schedule_id = ? AND (status IS NULL OR status <> 'cancelled')";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $schedule_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    return intval($result['total']);
}
// Lấy danh sách các đặt vé của 1 khách hàng
function getBookingsByCustomer($customer_id) {
    $conn = getDbConnection();
    // Join schedules and services so the view has service name, schedule date/time and price
    $sql = "SELECT b.booking_id, b.booking_ref, b.customer_id, b.schedule_id, b.num_people,
                   COALESCE(b.total_amount, 0) AS total, COALESCE(b.status, 'pending') AS status,
                   b.created_at, s.date, s.start_time, s.end_time, sv.service_id, sv.service_name, sv.price
            FROM bookings b
            LEFT JOIN schedules s ON b.schedule_id = s.schedule_id
            LEFT JOIN services sv ON s.service_id = sv.service_id
            WHERE b.customer_id = ?
            ORDER BY b.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $bookings;
}



function updateBookingStatus($booking_ref, $status) {
    $conn = getDbConnection();
    $sql = "UPDATE bookings SET status = ? WHERE booking_ref = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $status, $booking_ref);
    return mysqli_stmt_execute($stmt);
}

?>
