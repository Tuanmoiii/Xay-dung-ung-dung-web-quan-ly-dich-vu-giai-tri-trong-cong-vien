<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
checkLogin();
require_once __DIR__ . '/../../functions/customers_functions.php';

// Search handling: use GET param `q` to filter customers by name/email/phone
$q = trim($_GET['q'] ?? '');
if ($q !== '') {
  $customers = searchCustomers($q);
} else {
  $customers = getAllCustomers();
}
?>
<!DOCTYPE html>
<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Quản lý khách hàng</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2b9dee",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101a22",
                    },
                    fontFamily: {
                        "display": ["Be Vietnam Pro", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "1rem",
                        "xl": "1.5rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
<div class="flex min-h-screen">
<!-- SideNavBar (copied from dashboard to match menu) -->
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
<main class="flex-1 p-8">
<div class="max-w-7xl mx-auto">
<!-- PageHeading -->
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
<p class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Quản lý Khách hàng</p>
</div>
<!-- Control Bar -->
<div class="bg-white dark:bg-gray-900/50 p-4 rounded-xl shadow-sm mb-6">
<div class="flex flex-col md:flex-row justify-between gap-4">
<div class="flex-1 min-w-0">
<form method="get" class="w-full">
<label class="flex flex-col w-full">
<div class="flex w-full flex-1 items-stretch rounded-lg h-10">
<div class="text-gray-500 dark:text-gray-400 flex bg-gray-100 dark:bg-gray-800 items-center justify-center pl-3 rounded-l-lg">
<span class="material-symbols-outlined text-xl">search</span>
</div>
<input name="q" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border-none bg-gray-100 dark:bg-gray-800 h-full placeholder:text-gray-500 dark:placeholder:text-gray-400 px-4 rounded-l-none border-l-0 pl-2 text-sm font-normal leading-normal" placeholder="Tìm theo tên, email..." value="<?php echo htmlspecialchars($q); ?>"/>
<button type="submit" class="ml-2 inline-flex items-center rounded-lg bg-primary text-white px-4 h-10 hover:bg-primary/90">Tìm</button>
</div>
</label>
</form>
</div>
<div class="flex items-center gap-2">
<button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-gray-100 dark:bg-gray-800 px-4 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
<p class="text-sm font-medium leading-normal">Trạng thái</p>
<span class="material-symbols-outlined text-lg">arrow_drop_down</span>
</button>
<!-- Membership tier filter removed -->
 <a href="create.php" class="flex cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-primary text-white gap-2 text-sm font-bold leading-normal min-w-0 px-4 hover:bg-primary/90 transition-colors">
   <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1">add</span>
   <span class="truncate">Thêm Khách Hàng Mới</span>
 </a>
</div>
</div>
</div>
<!-- Data Table -->
<div class="bg-white dark:bg-gray-900/50 rounded-xl shadow-sm overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-sm text-left">
<thead class="bg-gray-50 dark:bg-gray-900 text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">
<tr>
<th class="px-6 py-3" scope="col">Tên Khách Hàng</th>
<th class="px-6 py-3" scope="col">Thông tin liên hệ</th>
<!-- Hạng thành viên column removed -->
<th class="px-6 py-3" scope="col">Trạng thái</th>
<th class="px-6 py-3" scope="col">Ngày tham gia</th>
<th class="px-6 py-3 text-right" scope="col">Hành động</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-200 dark:divide-gray-800">
<?php if (!empty($customers)): foreach ($customers as $c): ?>
<tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
<td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"><?php echo htmlspecialchars($c['full_name']); ?></td>
<td class="px-6 py-4 text-gray-600 dark:text-gray-300"><?php echo htmlspecialchars($c['email']); ?><?php if (!empty($c['phone'])) echo '<br/><span class="text-sm text-gray-500">'.htmlspecialchars($c['phone']).'</span>'; ?></td>
<!-- Membership tier cell removed for simpler layout -->
<td class="px-6 py-4">
    <?php $st = $c['status'] ?? 'active';
        if (strtolower($st)==='active' || $st==='Hoạt động') echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Hoạt động</span>';
        elseif (strtolower($st)==='locked' || $st==='Bị khóa') echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Bị khóa</span>';
        else echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300">'.htmlspecialchars($st).'</span>';
    ?>
</td>
<td class="px-6 py-4 text-gray-600 dark:text-gray-300"><?php echo htmlspecialchars(isset($c['created_at'])?substr($c['created_at'],0,10):'—'); ?></td>
<td class="px-6 py-4 text-right">
<div class="flex items-center justify-end gap-1">
<!-- visibility action removed per request -->
<a href="edit.php?id=<?php echo $c['customer_id']; ?>" class="p-1 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400"><span class="material-symbols-outlined text-lg">edit</span></a>
<form action="../../handle/customers_process.php" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa khách hàng này?');">
  <input type="hidden" name="customer_id" value="<?php echo $c['customer_id']; ?>">
  <button type="submit" name="delete" class="p-1 rounded-md hover:bg-red-100 dark:hover:bg-red-900/50 text-red-500"><span class="material-symbols-outlined text-lg">delete</span></button>
</form>
</div>
</td>
</tr>
<?php endforeach; else: ?>
<tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50"><td class="p-6 text-center" colspan="5">Chưa có khách hàng nào.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="flex items-center justify-between p-4 border-t border-gray-200 dark:border-gray-800">
<span class="text-sm text-gray-600 dark:text-gray-400">Hiển thị <span class="font-semibold text-gray-900 dark:text-white">1-<?php echo min(10,count($customers)); ?></span> trên <span class="font-semibold text-gray-900 dark:text-white"><?php echo count($customers); ?></span></span>
<div class="inline-flex rounded-lg shadow-sm">
<button class="relative inline-flex items-center px-3 py-2 rounded-l-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700" type="button">
<span class="material-symbols-outlined text-lg">chevron_left</span>
</button>
<button class="relative -ml-px inline-flex items-center px-3 py-2 rounded-r-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700" type="button">
<span class="material-symbols-outlined text-lg">chevron_right</span>
</button>
</div>
</div>
</div>
</main>
</div>
</body></html>
