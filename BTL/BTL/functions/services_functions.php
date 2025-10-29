<?php
// ===========================================
// services_functions.php
// Xử lý các dịch vụ / khu trò chơi
// ===========================================
require_once __DIR__ . '/db_connection.php';

/**
 * Lấy tất cả dịch vụ
 */
function getAllServices()
{
    $conn = getDbConnection();
    $sql = "SELECT s.*, p.park_name 
            FROM services s
            LEFT JOIN parks p ON s.park_id = p.park_id
            ORDER BY s.created_at DESC";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Thêm dịch vụ mới
 */
function addService($park_id, $service_name, $code, $description, $duration, $capacity, $price)
{
    $conn = getDbConnection();
    $sql = "INSERT INTO services (park_id, service_name, code, description, duration_minutes, capacity, price)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isssiid", $park_id, $service_name, $code, $description, $duration, $capacity, $price);
    return mysqli_stmt_execute($stmt);
}

/**
 * Lấy thông tin 1 dịch vụ theo ID
 */
function getServiceById($id)
{
    $conn = getDbConnection();
    $sql = "SELECT * FROM services WHERE service_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

/**
 * Cập nhật dịch vụ
 */
function updateService($id, $service_name, $description, $duration, $capacity, $price, $active)
{
    $conn = getDbConnection();
    $sql = "UPDATE services 
            SET service_name=?, description=?, duration_minutes=?, capacity=?, price=?, active=? 
            WHERE service_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssiidii", $service_name, $description, $duration, $capacity, $price, $active, $id);
    return mysqli_stmt_execute($stmt);
}

/**
 * Xóa dịch vụ
 */
function deleteService($id)
{
    $conn = getDbConnection();
    $sql = "DELETE FROM services WHERE service_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}
?>
