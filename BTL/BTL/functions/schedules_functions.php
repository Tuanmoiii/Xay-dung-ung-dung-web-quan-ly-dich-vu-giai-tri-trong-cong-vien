<?php
// ===========================================
// schedules_functions.php
// Quản lý lịch hoạt động dịch vụ
// ===========================================
require_once __DIR__ . '/db_connection.php';

/**
 * Lấy lịch hoạt động
 */
function getSchedulesByService($service_id)
{
    $conn = getDbConnection();
    $sql = "SELECT * FROM schedules WHERE service_id=? ORDER BY date, start_time";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $service_id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
}

/**
 * Lấy một lịch theo ID
 */
function getScheduleById($id)
{
    $conn = getDbConnection();
    $sql = "SELECT * FROM schedules WHERE schedule_id=? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($res);
}

/**
 * Thêm lịch
 */
function addSchedule($service_id, $date, $start, $end, $capacity)
{
    $conn = getDbConnection();
    $sql = "INSERT INTO schedules (service_id, date, start_time, end_time, capacity)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isssi", $service_id, $date, $start, $end, $capacity);
    return mysqli_stmt_execute($stmt);
}

/**
 * Cập nhật lịch
 */
function updateSchedule($id, $date, $start, $end, $capacity, $status)
{
    $conn = getDbConnection();
    $sql = "UPDATE schedules SET date=?, start_time=?, end_time=?, capacity=?, status=? WHERE schedule_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssisi", $date, $start, $end, $capacity, $status, $id);
    return mysqli_stmt_execute($stmt);
}

/**
 * Xóa lịch
 */
function deleteSchedule($id)
{
    $conn = getDbConnection();
    $sql = "DELETE FROM schedules WHERE schedule_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}
?>
