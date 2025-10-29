<?php
// ===========================================
// customers_functions.php
// Quản lý khách hàng
// ===========================================
require_once __DIR__ . '/db_connection.php';

function getAllCustomers()
{
    $conn = getDbConnection();
    $sql = "SELECT * FROM customers ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function addCustomer($name, $email, $phone)
{
    $conn = getDbConnection();
    $sql = "INSERT INTO customers (full_name, email, phone) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $phone);
    return mysqli_stmt_execute($stmt);
}

function getCustomerById($id)
{
    $conn = getDbConnection();
    $sql = "SELECT * FROM customers WHERE customer_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

function deleteCustomer($id)
{
    $conn = getDbConnection();
    $sql = "DELETE FROM customers WHERE customer_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}

function updateCustomer($id, $name, $email, $phone)
{
    $conn = getDbConnection();
    $sql = "UPDATE customers SET full_name=?, email=?, phone=? WHERE customer_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $phone, $id);
    return mysqli_stmt_execute($stmt);
}
?>
