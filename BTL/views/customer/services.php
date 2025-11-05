<?php
require_once("../../functions/db_connection.php");
require_once("../../functions/auth_functions.php");
checkLogin("../../index.php");
$conn = getDbConnection();

$result = mysqli_query($conn, "SELECT service_id, service_name, price, description FROM services");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Danh sách dịch vụ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
  <h3 class="text-center mb-4">Danh sách dịch vụ</h3>
  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>Tên dịch vụ</th>
        <th>Mô tả</th>
        <th>Giá</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?= htmlspecialchars($row['service_name']) ?></td>
        <td><?= htmlspecialchars($row['description']) ?></td>
        <td><?= number_format($row['price'], 0, ',', '.') ?> VNĐ</td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <a href="index.php" class="btn btn-secondary">⬅ Quay lại</a>
</div>
</body>
</html>
