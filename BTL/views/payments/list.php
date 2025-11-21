<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
require_once __DIR__ . '/../../functions/payments_functions.php';
checkLogin();

$payments = getPayments();
?>
<!DOCTYPE html>
<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Quản lý Thanh toán - Admin Dashboard</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
<script>
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": "#135bec",
              "background-light": "#f6f6f8",
              "background-dark": "#101622",
            },
            fontFamily: {
              "display": ["Inter", "sans-serif"]
            },
            borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
          },
        },
      }
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
<div class="flex min-h-screen w-full">
<!-- SideNavBar -->
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
<!-- Main Content -->
<main class="flex-1 p-8 overflow-auto">
<div class="mx-auto max-w-7xl">
<!-- PageHeading -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
<div class="flex flex-col">
<h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Quản lý Thanh toán</h1>
<p class="text-gray-500 dark:text-gray-400 mt-1">Xem, lọc và quản lý tất cả giao dịch trong hệ thống.</p>
</div>
<button class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold shadow-sm hover:bg-primary/90">
<span class="material-symbols-outlined text-base">download</span>
<span class="truncate">Xuất Báo cáo</span>
</button>
</div>
<!-- Toolbar & Data Table Container -->
<div class="w-full bg-white dark:bg-gray-900/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
<!-- SearchBar and Filters -->
<div class="flex flex-col gap-3 p-4 border-b border-gray-200 dark:border-gray-800">
<div class="flex items-center gap-4">
<div class="relative flex-grow">
<div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
<span class="material-symbols-outlined text-gray-500">search</span>
</div>
<input class="form-input block w-full rounded-lg border-gray-200 dark:border-gray-700 bg-background-light dark:bg-background-dark py-2.5 pl-10 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-500 focus:border-primary focus:ring-primary" placeholder="Tìm kiếm theo mã đặt vé, tên khách hàng..."/>
</div>
</div>
<div class="flex flex-wrap gap-3">
<button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent px-4 hover:bg-gray-100 dark:hover:bg-gray-800">
<p class="text-sm font-medium text-gray-700 dark:text-gray-300">Trạng thái</p>
<span class="material-symbols-outlined text-gray-500 text-base">expand_more</span>
</button>
<button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent px-4 hover:bg_gray-100 dark:hover:bg-gray-800">
<p class="text-sm font-medium text-gray-700 dark:text-gray-300">Phương thức thanh toán</p>
<span class="material-symbols-outlined text-gray-500 text-base">expand_more</span>
</button>
<button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent px-4 hover:bg-gray-100 dark:hover:bg-gray-800">
<p class="text-sm font-medium text-gray-700 dark:text-gray-300">Khoảng thời gian</p>
<span class="material-symbols-outlined text-gray-500 text-base">expand_more</span>
</button>
</div>
</div>
<!-- Data Table -->
<div class="overflow-x-auto">
<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
<thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-800">
<tr>
<th class="px-6 py-3" scope="col">Mã đặt vé</th>
<th class="px-6 py-3" scope="col">Khách hàng</th>
<th class="px-6 py-3" scope="col">Số tiền</th>
<th class="px-6 py-3" scope="col">Phương thức</th>
<th class="px-6 py-3" scope="col">Trạng thái</th>
<th class="px-6 py-3" scope="col">Ngày giao dịch</th>
<th class="px-6 py-3 text-center" scope="col">Hành động</th>
</tr>
</thead>
<tbody>
<?php if (!empty($payments)): foreach ($payments as $p):
    $status = strtolower($p['status'] ?? '');
    $badge = 'bg-slate-100 text-slate-800';
    if ($status === 'success' || $status === 'completed' || $status === 'thành công') { $badge = 'bg-green-50 text-green-600'; }
    elseif ($status === 'failed' || $status === 'thất bại') { $badge = 'bg-red-50 text-red-600'; }
    elseif ($status === 'pending' || $status === 'processing' || $status === 'đang xử lý') { $badge = 'bg-yellow-50 text-yellow-600'; }
?>
<tr class="bg-white dark:bg-gray-900/50 border-b dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
<td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"><?= htmlspecialchars($p['booking_ref'] ?? '') ?></td>
<td class="px-6 py-4"><?= htmlspecialchars($p['full_name'] ?? '') ?></td>
<td class="px-6 py-4"><?= number_format((float)($p['amount'] ?? 0), 0, ',', '.') ?> VNĐ</td>
<td class="px-6 py-4"><?= htmlspecialchars($p['method'] ?? '') ?></td>
<td class="px-6 py-4">
  <span class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-semibold <?= $badge ?>">
    <span class="h-1.5 w-1.5 rounded-full <?= ($badge === 'bg-green-50 text-green-600') ? 'bg-green-600' : (($badge === 'bg-red-50 text-red-600') ? 'bg-red-600' : 'bg-yellow-600') ?>"></span>
    <?= htmlspecialchars(ucfirst($p['status'] ?? '')) ?>
  </span>
</td>
<td class="px-6 py-4"><?= htmlspecialchars($p['paid_at'] ?? '') ?></td>
<td class="px-6 py-4 text-center">
  <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800" title="Xem"><span class="material-symbols-outlined text-base">visibility</span></button>
  <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800" title="In"><span class="material-symbols-outlined text-base">print</span></button>
</td>
</tr>
<?php endforeach; else: ?>
<tr class="bg-white dark:bg-gray-900/50 border-b dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50"><td class="p-6 text-center" colspan="7">Chưa có thanh toán nào.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
<!-- Pagination -->
<nav aria-label="Table navigation" class="flex items-center justify-between p-4">
<span class="text-sm font-normal text-gray-500 dark:text-gray-400">Hiển thị <span class="font-semibold text-gray-900 dark:text-white">1-10</span> trên <span class="font-semibold text-gray-900 dark:text-white"><?= count($payments) ?></span></span>
<ul class="inline-flex items-center -space-x-px">
<li>
<a class="px-3 h-8 ml-0 leading-tight text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-l-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-white flex items-center" href="#">Trước</a>
</li>
<li>
<a class="px-3 h-8 leading-tight text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-white flex items-center" href="#">1</a>
</li>
<li>
<a class="px-3 h-8 leading-tight text-primary bg-primary/10 border border-primary hover:bg-primary/20 hover:text-primary-700 flex items-center" href="#">2</a>
</li>
<li>
<a class="px-3 h-8 leading-tight text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-white flex items-center" href="#">...</a>
</li>
<li>
<a class="px-3 h-8 leading-tight text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-r-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-white flex items-center" href="#">Sau</a>
</li>
</ul>
</nav>
</div>
</main>
</div>
</body></html>