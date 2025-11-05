<?php
require_once("../../functions/db_connection.php");
require_once("../../functions/auth_functions.php");
checkLogin("../../index.php");

$conn = getDbConnection();
$currentUser = getCurrentUser();

// Lấy danh sách dịch vụ
$services = mysqli_query($conn, "SELECT service_id, service_name, price FROM services");
// Lấy danh sách lịch chiếu
$schedules = mysqli_query($conn, "SELECT schedule_id, schedule_time, service_id FROM schedules");

?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đặt vé dịch vụ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
  <h3 class="text-center mb-4">🎟 Đặt vé dịch vụ</h3>

  <form action="../../handle/bookings_process.php" method="POST" class="border p-4 bg-white rounded shadow-sm">
    <div class="mb-3">
      <label for="service_id" class="form-label">Chọn dịch vụ</label>
      <select class="form-select" name="service_id" id="service_id" required>
        <option value="">-- Chọn dịch vụ --</option>
        <?php while ($s = mysqli_fetch_assoc($services)): ?>
          <option value="<?= $s['service_id'] ?>">
            <?= htmlspecialchars($s['service_name']) ?> - <?= number_format($s['price'], 0, ',', '.') ?> VNĐ
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="schedule_id" class="form-label">Chọn lịch chiếu</label>
      <select class="form-select" name="schedule_id" id="schedule_id" required>
        <option value="">-- Chọn lịch chiếu --</option>
        <?php while ($sch = mysqli_fetch_assoc($schedules)): ?>
          <option value="<?= $sch['schedule_id'] ?>">
            <?= date('d/m/Y H:i', strtotime($sch['schedule_time'])) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="ticket_quantity" class="form-label">Số lượng vé</label>
      <input type="number" name="ticket_quantity" id="ticket_quantity" class="form-control" min="1" value="1" required>
    </div>

    <input type="hidden" name="customer_id" value="<?= $currentUser['id'] ?>">

    <div class="text-center">
      <button type="submit" name="book_ticket" class="btn btn-success btn-lg">Đặt vé</button>
      <a href="index.php" class="btn btn-secondary btn-lg ms-2">Quay lại</a>
    </div>
  </form>
</div>
</body>
</html>
