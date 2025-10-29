<?php
// ===========================================
// payments_functions.php
// Quản lý thanh toán
// ===========================================
require_once __DIR__ . '/db_connection.php';

function addPayment($booking_id, $amount, $method)
{
    $conn = getDbConnection();
    $sql = "INSERT INTO payments (booking_id, amount, method, status, paid_at)
            VALUES (?, ?, ?, 'success', NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ids", $booking_id, $amount, $method);
    return mysqli_stmt_execute($stmt);
}

function getPayments()
{
    $conn = getDbConnection();
    $sql = "SELECT p.*, b.booking_ref, c.full_name 
            FROM payments p
            JOIN bookings b ON p.booking_id = b.booking_id
            JOIN customers c ON b.customer_id = c.customer_id
            ORDER BY p.paid_at DESC";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>
