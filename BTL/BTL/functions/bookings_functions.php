<?php
// ===========================================
// bookings_functions.php
// Quản lý đặt vé
// ===========================================
require_once __DIR__ . '/db_connection.php';

function createBooking($customer_id, $schedule_id, $num_people, $total_amount)
{
    $conn = getDbConnection();
    $ref = 'BK' . strtoupper(uniqid());
    $sql = "INSERT INTO bookings (booking_ref, customer_id, schedule_id, num_people, total_amount)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "siiid", $ref, $customer_id, $schedule_id, $num_people, $total_amount);
    mysqli_stmt_execute($stmt);
    return $ref;
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
    $sql = "SELECT COALESCE(SUM(num_people), 0) as total FROM bookings WHERE schedule_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $schedule_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    return intval($result['total']);
}
?>
