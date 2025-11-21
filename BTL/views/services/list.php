<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
checkLogin();
require_once __DIR__ . '/../../functions/services_functions.php';

$services = getAllServices();
?>
<!DOCTYPE html>
<html class="" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Quản lý dịch vụ - Admin Dashboard</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings:
            'FILL' 0,
            'wght' 400,
            'GRAD' 0,
            'opsz' 24
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
              "display": ["Be Vietnam Pro", "sans-serif"]
            },
            borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
          },
        },
      }
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark font-display">
<div class="flex min-h-screen w-full">
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
<main class="flex-1 p-8">
<div class="w-full max-w-7xl mx-auto">
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
<h1 class="text-slate-900 dark:text-white text-3xl font-bold leading-tight tracking-tight">Quản lý dịch vụ</h1>
<a href="create.php" class="flex items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
<span class="material-symbols-outlined text-lg">add_circle</span>
<span class="truncate">Thêm dịch vụ mới</span>
</a>
</div>
<div class="bg-white dark:bg-[#1A2233] p-6 rounded-xl shadow-sm mb-6 border border-slate-200 dark:border-slate-700">
<h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Danh sách dịch vụ</h2>
<?php if (isset($_SESSION['error'])): ?>
                <div class="text-red-700 bg-red-100 p-3 rounded mb-4"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="text-green-700 bg-green-100 p-3 rounded mb-4"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
<div class="flex flex-col md:flex-row gap-4 mb-4">
<div class="flex-grow">
<label class="flex flex-col h-12 w-full">
<div class="flex w-full flex-1 items-stretch rounded-lg h-full bg-slate-100 dark:bg-[#1A2233]">
<div class="text-slate-500 dark:text-[#92a4c9] flex items-center justify-center pl-4">
<span class="material-symbols-outlined">search</span>
</div>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden text-slate-900 dark:text-white focus:outline-0 focus:ring-0 border-none bg-transparent h-full placeholder:text-slate-500 dark:placeholder:text-[#92a4c9] px-4 pl-2 text-base font-normal leading-normal" placeholder="Tìm kiếm theo tên dịch vụ..." value=""/>
</div>
</label>
</div>
<div class="flex gap-3 items-center">
<button class="flex h-12 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-slate-100 dark:bg-[#1A2233] px-4">
<p class="text-slate-900 dark:text-white text-sm font-medium leading-normal">Trạng thái: Tất cả</p>
<span class="material-symbols-outlined text-slate-500 dark:text-white text-xl">expand_more</span>
</button>
</div>
</div>
<div class="bg-slate-50 dark:bg-[#1A2233] rounded-xl overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-sm text-left text-slate-600 dark:text-slate-300">
<thead class="text-xs text-slate-700 dark:text-slate-400 uppercase bg-slate-100 dark:bg-[#232f48]">
<tr>
<th class="p-4" scope="col">
<div class="flex items-center">
<input class="w-4 h-4 text-primary bg-slate-200 border-slate-300 rounded focus:ring-primary dark:focus:ring-primary dark:ring-offset-background-dark dark:bg-slate-600 dark:border-slate-500" id="checkbox-all-search" type="checkbox"/>
<label class="sr-only" for="checkbox-all-search">checkbox</label>
</div>
</th>
<th class="px-6 py-3" scope="col">Tên dịch vụ</th>
<th class="px-6 py-3" scope="col">Mô tả ngắn</th>
<th class="px-6 py-3" scope="col">Gói vé</th>
<th class="px-6 py-3" scope="col">Trạng thái</th>
<th class="px-6 py-3" scope="col">Hành động</th>
</tr>
</thead>
<tbody>
<?php if (!empty($services)): foreach ($services as $s): 
    // prepare some fields with fallbacks
    $img = !empty($s['image']) ? $s['image'] : ('https://picsum.photos/seed/'.urlencode($s['service_id']).'/200/200');
    $category = $s['category'] ?? ($s['park_name'] ?? '-');
    $short = $s['short_description'] ?? $s['description'] ?? '-';
    $packages = isset($s['packages_count']) ? intval($s['packages_count']).' gói vé' : (isset($s['packages']) ? count($s['packages']).' gói vé' : '-');
    $status = strtolower($s['status'] ?? 'active');
    if ($status === 'active' || $status === 'đang hoạt động') { $badgeBg = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'; $dot = 'bg-green-500'; $label = 'Đang hoạt động'; }
    elseif ($status === 'maintenance' || $status === 'bảo trì') { $badgeBg = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'; $dot = 'bg-yellow-500'; $label = 'Bảo trì'; }
    elseif ($status === 'paused' || $status === 'tạm dừng') { $badgeBg = 'bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-300'; $dot = 'bg-slate-500'; $label = 'Tạm dừng'; }
    else { $badgeBg = 'bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-300'; $dot = 'bg-slate-500'; $label = htmlspecialchars($s['status']); }
?>
<tr class="bg-slate-50 dark:bg-[#1A2233] border-b border-slate-200 dark:border-[#232f48] hover:bg-slate-100 dark:hover:bg-[#232f48] transition-colors">
<td class="w-4 p-4">
<div class="flex items-center">
<input class="w-4 h-4 text-primary bg-slate-200 border-slate-300 rounded focus:ring-primary dark:focus:ring-primary dark:ring-offset-background-dark dark:bg-slate-600 dark:border-slate-500" type="checkbox"/>
<label class="sr-only">checkbox</label>
</div>
</td>
<th class="flex items-center px-6 py-4 text-slate-900 whitespace-nowrap dark:text-white" scope="row">
<img class="w-10 h-10 rounded-lg object-cover" data-alt="Ride image" src="<?php echo htmlspecialchars($img); ?>"/>
<div class="pl-3">
<div class="text-base font-semibold"><?php echo htmlspecialchars($s['service_name']); ?></div>
<div class="font-normal text-slate-500"><?php echo htmlspecialchars($category); ?></div>
</div>
</th>
<td class="px-6 py-4"><?php echo htmlspecialchars(mb_strimwidth($short,0,120,'...')); ?></td>
<td class="px-6 py-4"><?php echo $packages; ?></td>
<td class="px-6 py-4">
<div class="flex items-center">
<span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium <?php echo $badgeBg; ?>">
<span class="size-1.5 inline-block rounded-full <?php echo $dot; ?>"></span>
<?php echo $label; ?>
</span>
</div>
</td>
<td class="px-6 py-4">
<div class="flex items-center gap-2">
<a href="edit.php?id=<?php echo $s['service_id']; ?>" class="p-2 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors"><span class="material-symbols-outlined text-slate-600 dark:text-slate-300">edit</span></a>
<form action="../../handle/services_process.php" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa dịch vụ này?');">
    <input type="hidden" name="service_id" value="<?php echo $s['service_id']; ?>">
    <button type="submit" name="delete" class="p-2 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors"><span class="material-symbols-outlined text-red-500">delete</span></button>
</form>
<a href="../schedules/list.php?service_id=<?php echo $s['service_id']; ?>" class="p-2 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors"><span class="material-symbols-outlined text-slate-600 dark:text-slate-300">confirmation_number</span></a>
</div>
</td>
</tr>
<?php endforeach; else: ?>
<tr class="bg-slate-50 dark:bg-[#1A2233] hover:bg-slate-100 dark:hover:bg-[#232f48] transition-colors"><td class="p-6 text-center" colspan="6">Chưa có dịch vụ nào.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
<nav aria-label="Table navigation" class="flex items-center justify-between p-4">
<span class="text-sm font-normal text-slate-500 dark:text-slate-400">Hiển thị <span class="font-semibold text-slate-900 dark:text-white">1-5</span> trên <span class="font-semibold text-slate-900 dark:text-white"><?php echo count($services); ?></span></span>
<ul class="inline-flex -space-x-px text-sm h-8">
<li>
<a class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-slate-500 bg-white border border-slate-300 rounded-l-lg hover:bg-slate-100 hover:text-slate-700 dark:bg-[#1A2233] dark:border-slate-700 dark:text-slate-400 dark:hover:bg-[#232f48] dark:hover:text-white" href="#">Trước</a>
</li>
<li>
<a aria-current="page" class="flex items-center justify-center px-3 h-8 text-primary border border-slate-300 bg-primary/20 hover:bg-primary/30 dark:border-slate-700 dark:bg-primary/30 dark:text-white" href="#">1</a>
</li>
<li>
<a class="flex items-center justify-center px-3 h-8 leading-tight text-slate-500 bg-white border border-slate-300 hover:bg-slate-100 hover:text-slate-700 dark:bg-[#1A2233] dark:border-slate-700 dark:text-slate-400 dark:hover:bg-[#232f48] dark:hover:text-white" href="#">2</a>
</li>
<li>
<a class="flex items-center justify-center px-3 h-8 leading-tight text-slate-500 bg-white border border-slate-300 hover:bg-slate-100 hover:text-slate-700 dark:bg-[#1A2233] dark:border-slate-700 dark:text-slate-400 dark:hover:bg-[#232f48] dark:hover:text-white" href="#">...</a>
</li>
<li>
<a class="flex items-center justify-center px-3 h-8 leading-tight text-slate-500 bg-white border border-slate-300 rounded-r-lg hover:bg-slate-100 hover:text-slate-700 dark:bg-[#1A2233] dark:border-slate-700 dark:text-slate-400 dark:hover:bg-[#232f48] dark:hover:text-white" href="#">Sau</a>
</li>
</ul>
</nav>
</div>
</div>
</main>
</div>
</body></html>
