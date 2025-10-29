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
    $sql = "SELECT b.*, c.full_name, s.date, s.start_time, s.end_time 
            FROM bookings b
            JOIN customers c ON b.customer_id = c.customer_id
            JOIN schedules s ON b.schedule_id = s.schedule_id
            WHERE b.booking_ref = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $ref);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}
?>
