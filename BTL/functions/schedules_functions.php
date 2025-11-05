<?php
// ===========================================
// schedules_functions.php
// Quản lý lịch hoạt động dịch vụ
// ===========================================

require_once __DIR__ . '/db_connection.php';

/**
 * Lấy danh sách lịch hoạt động theo ID dịch vụ
 */
function getSchedulesByService($service_id)
{
    $conn = getDbConnection();
    $sql = "SELECT * FROM schedules WHERE service_id=? ORDER BY date, start_time";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $service_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $data;
}

/**
 * Lấy danh sách lịch chiếu (showtime) theo ID dịch vụ
 * -> Dùng cho giao diện Customer
 */
function getSchedulesByServiceId($service_id)
{
    $conn = getDbConnection();

    $sql = "SELECT schedule_id, date, start_time, end_time, capacity, status
            FROM schedules
            WHERE service_id = ?
            ORDER BY date ASC, start_time ASC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $service_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $schedules = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $schedules;
}


/**
 * Lấy chi tiết 1 lịch hoạt động theo ID
 */
function getScheduleById($id)
{
    $conn = getDbConnection();
    $sql = "SELECT * FROM schedules WHERE schedule_id=? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($res);
    mysqli_close($conn);
    return $data;
}

/**
 * Thêm mới lịch hoạt động
 */
function addSchedule($service_id, $date, $start, $end, $capacity)
{
    $conn = getDbConnection();
    $sql = "INSERT INTO schedules (service_id, date, start_time, end_time, capacity)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isssi", $service_id, $date, $start, $end, $capacity);
    $result = mysqli_stmt_execute($stmt);
    mysqli_close($conn);
    return $result;
}

/**
 * Cập nhật lịch hoạt động
 */
function updateSchedule($id, $date, $start, $end, $capacity, $status)
{
    $conn = getDbConnection();
    $sql = "UPDATE schedules 
            SET date=?, start_time=?, end_time=?, capacity=?, status=? 
            WHERE schedule_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssisi", $date, $start, $end, $capacity, $status, $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_close($conn);
    return $result;
}

/**
 * Xóa lịch hoạt động
 */
function deleteSchedule($id)
{
    $conn = getDbConnection();
    $sql = "DELETE FROM schedules WHERE schedule_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_close($conn);
    return $result;
}
?>
