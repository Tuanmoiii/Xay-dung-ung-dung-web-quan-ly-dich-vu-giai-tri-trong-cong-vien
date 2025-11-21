<?php
require_once __DIR__ . '/../../functions/auth_functions.php';
require_once __DIR__ . '/../../functions/db_connection.php';
require_once __DIR__ . '/../../functions/bookings_functions.php';
require_once __DIR__ . '/../../functions/customers_functions.php';
require_once __DIR__ . '/../../functions/services_functions.php';

checkAdmin('../../index.php');
$currentUser = getCurrentUser();

$conn = getDbConnection();
// Compute basic stats
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(total_amount),0) AS total FROM bookings"));
$totalRevenue = $row ? $row['total'] : 0;
$row2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(num_people),0) AS tickets FROM bookings"));
$ticketsSold = $row2 ? $row2['tickets'] : 0;
$row3 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM customers"));
$totalCustomers = $row3 ? $row3['c'] : 0;
$row4 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM services WHERE active = 1"));
$activeServices = $row4 ? $row4['c'] : 0;

$recentBookings = array_slice(getAllBookings(), 0, 6);

// Get last 7 days revenue (date => total)
$revenueData = [];
$days = [];
$res = mysqli_query($conn, "SELECT DATE(created_at) as d, COALESCE(SUM(total_amount),0) AS total FROM bookings WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) GROUP BY DATE(created_at) ORDER BY DATE(created_at) ASC");
$map = [];
while ($r = mysqli_fetch_assoc($res)) {
  $map[$r['d']] = floatval($r['total']);
}
for ($i = 6; $i >= 0; $i--) {
  $date = date('Y-m-d', strtotime("-{$i} days"));
  $days[] = $date;
  $revenueData[] = isset($map[$date]) ? $map[$date] : 0;
}
$maxRev = max($revenueData) ?: 1; // avoid divide by zero

?>
<!DOCTYPE html>
<html lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>ParkAdmin Dashboard</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
<script>
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": {
                DEFAULT: "#2563eb",
                light: "#dbeafe",
                dark: "#1e40af"
              },
              "background-light": "#f0f9ff",
              "background-dark": "#101622",
            },
            fontFamily: {
              "display": ["Be Vietnam Pro", "sans-serif"]
            },
            borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings:
            'FILL' 0,
            'wght' 400,
            'GRAD' 0,
            'opsz' 24
        }
    </style>
</head>
<body class="font-display bg-background-light">
<div class="flex h-screen w-full">
<aside class="flex w-64 flex-col bg-white p-4 text-slate-800 shadow-lg">
  <div class="flex flex-col gap-4">
    <div class="flex items-center gap-3 px-3">
      <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="ParkAdmin logo" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCpZvXhTGHB9Juv75zKOh0hhux9SIEV-c9ptxDZ8f46jELYu0vy0FpxuzxlK_DOEihB04DR9h8VbZYlbXmK7daqIskHdadTHLA2NV1gSwjGVcTRXz7hMEl8kBy783saHdBMcfZ-fvfnVCFZ7GJY1Jk1SMkxWmggd6U0Rf4_YhutEPYk35-NEaFd14PoOmGCUKsHE3vwgrqWrAiOUDUYbmSSl2TJIGSME123hS-TTVIzalAyzlQNgRv4ioOUR0eMZrLMxW7q34WQmcfz");'></div>
      <div class="flex flex-col">
        <h1 class="text-slate-800 text-base font-bold leading-normal">ParkAdmin</h1>
        <p class="text-slate-500 text-sm font-normal leading-normal">Quản lý Dịch vụ</p>
      </div>
    </div>
    <nav class="mt-4 flex flex-col gap-2">
      <a class="flex items-center gap-3 rounded-lg bg-primary-light px-3 py-2 text-primary-dark font-medium" href="../dashboard/index.php">
        <span class="material-symbols-outlined">dashboard</span>
        <p class="text-sm leading-normal">Tổng quan</p>
      </a>
      <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-600 hover:bg-primary-light hover:text-primary-dark" href="../services/list.php">
        <span class="material-symbols-outlined">local_activity</span>
        <p class="text-sm font-medium leading-normal">Quản lý Dịch vụ</p>
      </a>
      <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-600 hover:bg-primary-light hover:text-primary-dark" href="../schedules/list.php">
        <span class="material-symbols-outlined">calendar_month</span>
        <p class="text-sm font-medium leading-normal">Quản lý Lịch chiếu</p>
      </a>
      <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-600 hover:bg-primary-light hover:text-primary-dark" href="../customers/list.php">
        <span class="material-symbols-outlined">group</span>
        <p class="text-sm font-medium leading-normal">Quản lý Khách hàng</p>
      </a>
      <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-600 hover:bg-primary-light hover:text-primary-dark" href="../bookings/history.php">
        <span class="material-symbols-outlined">confirmation_number</span>
        <p class="text-sm font-medium leading-normal">Quản lý Đặt vé</p>
      </a>
      <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-600 hover:bg-primary-light hover:text-primary-dark" href="../payments/list.php">
        <span class="material-symbols-outlined">credit_card</span>
        <p class="text-sm font-medium leading-normal">Quản lý Thanh toán</p>
      </a>
    </nav>
  </div>
  <div class="mt-auto flex flex-col gap-1">
    <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-600 hover:bg-primary-light hover:text-primary-dark" href="../../handle/logout_process.php">
      <span class="material-symbols-outlined">logout</span>
      <p class="text-sm font-medium leading-normal">Đăng xuất</p>
    </a>
  </div>
</aside>
<main class="flex-1 overflow-y-auto p-8">
  <div class="mx-auto max-w-7xl">
    <div class="flex flex-wrap items-center justify-between gap-4">
      <p class="text-slate-800 text-3xl font-bold leading-tight tracking-tight">Bảng điều khiển Tổng quan</p>

    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
      <div class="flex flex-1 flex-col gap-2 rounded-xl bg-white p-6 border border-slate-200 shadow-sm">
        <p class="text-slate-600 text-base font-medium leading-normal">Doanh thu</p>
        <p class="text-slate-800 tracking-tight text-3xl font-bold leading-tight"><?= number_format($totalRevenue) ?>đ</p>
        <p class="text-green-500 text-base font-medium leading-normal">&nbsp;</p>
      </div>
      <div class="flex flex-1 flex-col gap-2 rounded-xl bg-white p-6 border border-slate-200 shadow-sm">
        <p class="text-slate-600 text-base font-medium leading-normal">Tổng số vé bán ra</p>
        <p class="text-slate-800 tracking-tight text-3xl font-bold leading-tight"><?= number_format($ticketsSold) ?></p>
        <p class="text-green-500 text-base font-medium leading-normal">&nbsp;</p>
      </div>
      <div class="flex flex-1 flex-col gap-2 rounded-xl bg-white p-6 border border-slate-200 shadow-sm">
        <p class="text-slate-600 text-base font-medium leading-normal">Số lượng khách check-in</p>
        <p class="text-slate-800 tracking-tight text-3xl font-bold leading-tight"><?= number_format($totalCustomers) ?></p>
        <p class="text-green-500 text-base font-medium leading-normal">&nbsp;</p>
      </div>
      <div class="flex flex-1 flex-col gap-2 rounded-xl bg-white p-6 border border-slate-200 shadow-sm">
        <p class="text-slate-600 text-base font-medium leading-normal">Lịch chiếu đang hoạt động</p>
        <p class="text-slate-800 tracking-tight text-3xl font-bold leading-tight"><?= number_format($activeServices) ?></p>
        <p class="text-red-500 text-base font-medium leading-normal">&nbsp;</p>
      </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-5">
      <div class="lg:col-span-3 flex flex-col gap-2 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-slate-800 text-lg font-semibold leading-normal">Doanh thu (7 ngày gần nhất)</p>
        <p class="text-slate-800 tracking-tight text-[32px] font-bold leading-tight truncate"><?= number_format(array_sum($revenueData)) ?>đ</p>
        <div class="flex gap-1">
          <p class="text-slate-500 text-base font-normal leading-normal">Tuần này</p>
          <p class="text-green-500 text-base font-medium leading-normal">&nbsp;</p>
        </div>
        <div class="flex min-h-[180px] flex-1 flex-col gap-8 py-4">
          <svg fill="none" height="148" preserveAspectRatio="none" viewBox="-3 0 478 150" width="100%" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 109C18.1538 109 18.1538 21 36.3077 21C54.4615 21 54.4615 41 72.6154 41C90.7692 41 90.7692 93 108.923 93C127.077 93 127.077 33 145.231 33C163.385 33 163.385 101 181.538 101C199.692 101 199.692 61 217.846 61C236 61 236 45 254.154 45C272.308 45 272.308 121 290.462 121C308.615 121 308.615 149 326.769 149C344.923 149 344.923 1 363.077 1C381.231 1 381.231 81 399.385 81C417.538 81 417.538 129 435.692 129C453.846 129 453.846 25 472 25V149H326.769H0V109Z" fill="url(#paint0_linear_1131_5935)"></path>
            <path d="M0 109C18.1538 109 18.1538 21 36.3077 21C54.4615 21 54.4615 41 72.6154 41C90.7692 41 90.7692 93 108.923 93C127.077 93 127.077 33 145.231 33C163.385 33 163.385 101 181.538 101C199.692 101 199.692 61 217.846 61C236 61 236 45 254.154 45C272.308 45 272.308 121 290.462 121C308.615 121 308.615 149 326.769 149C344.923 149 344.923 1 363.077 1C381.231 1 381.231 81 399.385 81C417.538 81 417.538 129 435.692 129C453.846 129 453.846 25 472 25" stroke="#2563eb" stroke-linecap="round" stroke-width="3"></path>
            <defs>
              <linearGradient gradientUnits="userSpaceOnUse" id="paint0_linear_1131_5935" x1="236" x2="236" y1="1" y2="149">
                <stop stop-color="#2563eb" stop-opacity="0.3"></stop>
                <stop offset="1" stop-color="#2563eb" stop-opacity="0"></stop>
              </linearGradient>
            </defs>
          </svg>
          <div class="flex justify-around">
            <p class="text-slate-500 text-sm font-medium leading-normal">T2</p>
            <p class="text-slate-500 text-sm font-medium leading-normal">T3</p>
            <p class="text-slate-500 text-sm font-medium leading-normal">T4</p>
            <p class="text-slate-500 text-sm font-medium leading-normal">T5</p>
            <p class="text-slate-500 text-sm font-medium leading-normal">T6</p>
            <p class="text-slate-500 text-sm font-medium leading-normal">T7</p>
            <p class="text-slate-500 text-sm font-medium leading-normal">CN</p>
          </div>
        </div>
      </div>

      <div class="lg:col-span-2 flex flex-col gap-2 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-slate-800 text-lg font-semibold leading-normal">Tỷ trọng Dịch vụ</p>
        <p class="text-slate-800 tracking-tight text-[32px] font-bold leading-tight truncate">Tổng: <?= number_format($ticketsSold) ?> vé</p>
        <div class="flex gap-1">
          <p class="text-slate-500 text-base font-normal leading-normal">Tháng này</p>
          <p class="text-green-500 text-base font-medium leading-normal">&nbsp;</p>
        </div>
        <div class="grid min-h-[180px] grid-flow-col gap-6 grid-rows-[1fr_auto] items-end justify-items-center px-3 pt-4">
          <div class="w-full rounded-t-lg bg-primary-light" style="height: 80%;"></div>
          <p class="text-slate-500 text-sm font-medium leading-normal">Tàu lượn</p>
          <div class="w-full rounded-t-lg bg-primary-light" style="height: 70%;"></div>
          <p class="text-slate-500 text-sm font-medium leading-normal">Vòng quay</p>
          <div class="w-full rounded-t-lg bg-primary-light" style="height: 20%;"></div>
          <p class="text-slate-500 text-sm font-medium leading-normal">Nhà ma</p>
          <div class="w-full rounded-t-lg bg-primary-light" style="height: 90%;"></div>
          <p class="text-slate-500 text-sm font-medium leading-normal">Xe đụng</p>
          <div class="w-full rounded-t-lg bg-primary-light" style="height: 80%;"></div>
          <p class="text-slate-500 text-sm font-medium leading-normal">Phim 5D</p>
        </div>
      </div>
    </div>

    <!-- Recent bookings (moved down for clarity) -->
    <div class="mt-6 flex flex-col rounded-xl border border-slate-200 bg-white shadow-sm">
      <div class="p-6">
        <h3 class="text-slate-800 text-lg font-semibold leading-normal">Đặt vé gần đây</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="border-b border-slate-200 text-slate-500">
            <tr>
              <th class="px-6 py-3 font-medium">Mã vé</th>
              <th class="px-6 py-3 font-medium">Khách hàng</th>
              <th class="px-6 py-3 font-medium">Dịch vụ</th>
              <th class="px-6 py-3 font-medium">Thời gian</th>
              <th class="px-6 py-3 font-medium">Trạng thái</th>
            </tr>
          </thead>
          <tbody class="text-slate-800">
            <?php foreach ($recentBookings as $b): ?>
              <tr class="border-b border-slate-200">
                <td class="px-6 py-4"><?= htmlspecialchars($b['booking_ref']) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($b['full_name']) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($b['service_name'] ?? '—') ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars(($b['date'] ?? '') . ' ' . ($b['start_time'] ?? '')) ?></td>
                <td class="px-6 py-4">
                      <?php $st = strtoupper(trim($b['status'] ?? ''));
                        if ($st === 'PAID' || $st === 'SUCCESS' || $st === 'COMPLETED'): ?>
                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Đã thanh toán</span>
                      <?php elseif ($st === 'PENDING' || $st === 'PROCESSING' || $st === 'WAITING'): ?>
                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Đang chờ</span>
                      <?php elseif ($st === 'CANCELLED' || $st === 'CANCELED' || $st === 'FAILED'): ?>
                        <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Đã hủy</span>
                      <?php else: ?>
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800"><?= htmlspecialchars($b['status'] ?? '—') ?></span>
                      <?php endif; ?>
                    </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</main>
</div>

</body></html>
