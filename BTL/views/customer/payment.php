<?php
require_once("../../functions/db_connection.php");
require_once("../../functions/auth_functions.php");
checkLogin("../../index.php");

$conn = getDbConnection();
$currentUser = getCurrentUser();

// Lấy danh sách các vé chưa thanh toán
$sql = "
SELECT 
  b.booking_id, 
  s.service_name, 
  b.ticket_quantity, 
  b.total_price, 
  b.booking_date 
FROM bookings b
JOIN services s ON b.service_id = s.service_id
WHERE b.customer_id = ? AND b.payment_status = 'pending'";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $currentUser['id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thanh toán</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
  <h3 class="text-center mb-4">💳 Thanh toán vé</h3>

  <?php if (mysqli_num_rows($result) > 0): ?>
  <form action="../../handle/payments_process.php" method="POST">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>Dịch vụ</th>
          <th>Số lượng vé</th>
          <th>Tổng tiền</th>
          <th>Ngày đặt</th>
          <th>Chọn</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= htmlspecialchars($row['service_name']) ?></td>
          <td><?= $row['ticket_quantity'] ?></td>
          <td><?= number_format($row['total_price'], 0, ',', '.') ?> VNĐ</td>
          <td><?= date('d/m/Y', strtotime($row['booking_date'])) ?></td>
          <td><input type="checkbox" name="booking_ids[]" value="<?= $row['booking_id'] ?>"></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <div class="mb-3">
      <label for="payment_method" class="form-label">Phương thức thanh toán</label>
      <select class="form-select" name="payment_method" id="payment_method" required>
        <option value="">-- Chọn phương thức --</option>
        <option value="cash">Tiền mặt</option>
        <option value="banking">Chuyển khoản</option>
      </select>
    </div>

    <div class="text-center">
      <button type="submit" name="pay_now" class="btn btn-primary btn-lg">Thanh toán</button>
      <a href="index.php" class="btn btn-secondary btn-lg ms-2">Quay lại</a>
    </div>
  </form>
  <?php else: ?>
    <div class="alert alert-info text-center">Hiện tại bạn không có vé nào cần thanh toán.</div>
  <?php endif; ?>
</div>
</body>
</html>
