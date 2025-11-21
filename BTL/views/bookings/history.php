<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
require_once __DIR__ . '/../../functions/bookings_functions.php';

checkLogin();
$searchQuery = $_GET['q'] ?? '';
if (!empty($keyword)) {
    $bookings = searchBookings($keyword);
} else {
    $bookings = getAllBookings();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quản lý Đặt vé</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;900&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { 'primary': { DEFAULT: '#2563eb', light: '#dbeafe', dark: '#1e40af' } },
          fontFamily: { 'display': ['Be Vietnam Pro', 'sans-serif'] }
        }
      }
    }
  </script>
  <style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24 }
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
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
          <p class="text-slate-800 text-3xl font-bold leading-tight tracking-tight">Lịch sử đặt vé</p>
          <form method="get" class="flex items-center gap-2">
            <input name="q" class="border rounded-md px-3 py-2" placeholder="Tìm theo tên, mã đặt vé" />
            <button class="bg-primary text-white px-3 py-2 rounded-md">Tìm</button>
          </form>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
              <thead class="border-b border-slate-200 text-slate-500">
                <tr>
                  <th class="px-6 py-3 font-medium">Mã vé</th>
                  <th class="px-6 py-3 font-medium">Khách hàng</th>
                  <th class="px-6 py-3 font-medium">Dịch vụ</th>
                  <th class="px-6 py-3 font-medium">Thời gian</th>
                  <th class="px-6 py-3 font-medium">Trạng thái</th>
                  <th class="px-6 py-3 font-medium">Hành động</th>
                </tr>
              </thead>
              <tbody class="text-slate-800">
                <?php if (!empty($bookings)): foreach ($bookings as $b): ?>
                  <tr class="border-b border-slate-200">
                    <td class="px-6 py-4"><?= htmlspecialchars($b['booking_ref']) ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($b['full_name']) ?><div class="text-xs text-slate-400"><?= htmlspecialchars($b['email'] ?? '') . ' ' . htmlspecialchars($b['phone'] ?? '') ?></div></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($b['service_name'] ?? '—') ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars(($b['date'] ?? '') . ' ' . ($b['start_time'] ?? '')) ?></td>
                    <td class="px-6 py-4">
                      <?php $st = ($b['status'] ?? '');
                        if ($st === 'PAID' || strtolower($st) === 'paid'): ?>
                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Đã thanh toán</span>
                      <?php elseif ($st === 'PENDING' || strtolower($st) === 'pending'): ?>
                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Chờ thanh toán</span>
                      <?php elseif ($st === 'CANCELLED' || strtolower($st) === 'cancelled'): ?>
                        <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Đã hủy</span>
                      <?php else: ?>
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800"><?= htmlspecialchars($st ?: '—') ?></span>
                      <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                      <div class="flex items-center justify-end gap-2">
                        <a href="confirm.php?ref=<?= urlencode($b['booking_ref']) ?>" class="p-2 rounded-md bg-sky-500 text-white text-sm">Chi tiết</a>
                        <?php if (strtolower($b['status'] ?? '') !== 'confirmed' && strtolower($b['status'] ?? '') !== 'paid'): ?>
                          <form action="../../handle/bookings_process.php" method="POST" onsubmit="return confirm('Xác nhận hủy đặt vé?');">
                            <input type="hidden" name="action" value="cancel">
                            <input type="hidden" name="booking_ref" value="<?= htmlspecialchars($b['booking_ref']) ?>">
                            <button type="submit" class="p-2 rounded-md bg-rose-500 text-white text-sm">Hủy</button>
                          </form>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; else: ?>
                  <tr><td colspan="6" class="p-6 text-center text-slate-500">Chưa có đặt vé nào.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
