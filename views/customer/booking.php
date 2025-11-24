<?php
require_once("../../functions/db_connection.php");
require_once("../../functions/auth_functions.php");
require_once("../../functions/services_functions.php");
require_once("../../functions/schedules_functions.php");

checkLogin("../../index.php");

$conn = getDbConnection();
$currentUser = getCurrentUser();

// Load all services (dropdown)
$services = mysqli_query($conn, "SELECT service_id, service_name, price FROM services ORDER BY service_name");

// Load schedules of selected service
$selectedServiceId = isset($_GET['service_id']) ? intval($_GET['service_id']) : 0;
$schedules = ($selectedServiceId > 0) ? getSchedulesByServiceId($selectedServiceId) : [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đặt vé dịch vụ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font optional -->
  <?php 
  if (!empty($_GET['font'])) {
      $allowed = [
          "Be Vietnam Pro" => "https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap",
          "Inter" => "https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap"
      ];
      $requested = trim($_GET['font']);
      if (isset($allowed[$requested])) {
          echo '<link href="'.$allowed[$requested].'" rel="stylesheet" />';
          echo "<style>body{font-family:'".$requested."', sans-serif;}</style>";
      }
  }
  ?>
</head>

<body class="bg-light">

<div class="container mt-4">
  <h3 class="text-center mb-4">🎟 Đặt vé dịch vụ</h3>

  <form action="../../handle/bookings_process.php" method="POST" class="border p-4 bg-white rounded shadow-sm">

    <!-- Chọn dịch vụ -->
    <div class="mb-3">
      <label for="service_id" class="form-label">Chọn dịch vụ</label>
      <select class="form-select" name="service_id" id="service_id" required onchange="location.href='?service_id='+this.value">
        <option value="">-- Chọn dịch vụ --</option>
        <?php while ($s = mysqli_fetch_assoc($services)): ?>
          <option value="<?= $s['service_id'] ?>" 
            <?= ($selectedServiceId == $s['service_id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($s['service_name']) ?> - 
            <?= number_format($s['price'], 0, ',', '.') ?> VNĐ
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <!-- Chọn lịch chiếu -->
    <div class="mb-3">
      <label for="schedule_id" class="form-label">Chọn lịch chiếu</label>
      <select class="form-select" name="schedule_id" id="schedule_id" required>
        <option value="">-- Chọn lịch chiếu --</option>

        <?php foreach ($schedules as $sch): ?>
          <option value="<?= $sch['schedule_id'] ?>">
            <?= htmlspecialchars($sch['date'] . " " . $sch['start_time']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- Số lượng vé -->
    <div class="mb-3">
      <label for="ticket_quantity" class="form-label">Số lượng vé</label>
      <input type="number" name="ticket_quantity" id="ticket_quantity" 
             class="form-control" min="1" value="1" required>
    </div>

    <!-- ID khách hàng -->
    <input type="hidden" name="customer_id" value="<?= $_SESSION['customer_id'] ?>">

    <!-- Buttons -->
    <div class="text-center">
      <button type="submit" name="book_ticket" class="btn btn-success btn-lg">Đặt vé</button>
      <a href="index.php" class="btn btn-secondary btn-lg ms-2">Quay lại</a>
    </div>

  </form>
</div>

</body>
</html>
