<?php
require_once("../../functions/db_connection.php");
require_once("../../functions/auth_functions.php");
checkLogin("../../index.php");
$conn = getDbConnection();

$res = mysqli_query($conn, "SELECT service_id, service_name, price, description FROM services ORDER BY service_name ASC");
$services = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Dịch vụ của tôi - Park Entertainment</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&amp;display=swap" rel="stylesheet"/>
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
<script id="tailwind-config">
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
            "display": ["Inter"]
          },
          borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
        },
      },
    }
  </script>
</head>
<body class="font-display bg-background-light dark:bg-background-dark">
<div class="relative flex w-full min-h-screen">
    <!-- SideNavBar (copied from customer index) -->
    <aside class="sticky top-0 h-screen w-64 flex-shrink-0 bg-[#111722] p-4 flex flex-col justify-between">
      <div class="flex flex-col gap-8">
        <div class="flex items-center gap-3 px-2">
          <span class="material-symbols-outlined text-primary text-3xl">local_activity</span>
          <p class="text-white text-xl font-bold">VuiChơi Park</p>
        </div>
        <div class="flex flex-col gap-4">
          <div class="flex items-center gap-3">
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="Avatar" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAMZrxcvUMtSowx35KWNCUBHFocd0M6F6DR638lsJi9eF7sL5aUOH6RZ1w68F-iachjQULKizv3WE9AOnx8_uLm-AfaWGb4YUcPodhvm_ZN5pE1UyVwesl8ucnDyOGmucjzxIEDXjVeRR0YL6ZVFay9rCEEpnbt8iuaardCE4J79_Zp-w7fw7M3EEPFeJiWNHuWGcmmNVbdVqIYUNWziHaGeqsio-Pr-EgVHTv7GmJ-mxPxofRaMOjKwttXyOuW7P9lxu-D6CQDA49P");'></div>
            <div class="flex flex-col">
              <h1 class="text-white text-base font-medium leading-normal"><?php echo htmlspecialchars($user['full_name'] ?? $user['name'] ?? $user['username'] ?? 'Khách'); ?></h1>
              <p class="text-[#92a4c9] text-sm font-normal leading-normal">Thành viên</p>
            </div>
          </div>
          <nav class="flex flex-col gap-2">
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors" href="../customer/index.php">
              <span class="material-symbols-outlined text-white text-xl">dashboard</span>
              <p class="text-white text-sm font-medium leading-normal">Tổng quan</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors" href="../customer/profile.php">
              <span class="material-symbols-outlined text-white text-xl">person</span>
              <p class="text-white text-sm font-medium leading-normal">Thông tin cá nhân</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors" href="history.php">
              <span class="material-symbols-outlined text-white text-xl">history</span>
              <p class="text-white text-sm font-medium leading-normal">Lịch sử đặt vé</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/30" href="../customer/services.php">
              <span class="material-symbols-outlined text-white text-xl">confirmation_number</span>
              <p class="text-white text-sm font-medium leading-normal">Dịch vụ của tôi</p>
            </a>
          </nav>
        </div>
      </div>
      <div class="flex flex-col gap-1">
        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors" href="#">
          <span class="material-symbols-outlined text-white text-xl">settings</span>
          <p class="text-white text-sm font-medium leading-normal">Cài đặt</p>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors" href="../../handle/logout_process.php">
          <span class="material-symbols-outlined text-white text-xl">logout</span>
          <p class="text-white text-sm font-medium leading-normal">Đăng xuất</p>
        </a>
      </div>
    </aside>

  <div class="layout-container flex h-full grow flex-col">
  <div class="flex flex-1 justify-center py-5">
  <div class="layout-content-container flex flex-col w-full max-w-5xl flex-1 px-4 md:px-0">
<main class="mt-8 flex flex-col gap-6">
  <!-- PageHeading -->
  <div class="flex flex-wrap justify-between gap-3 p-4">
    <div class="flex min-w-72 flex-col gap-2">
      <p class="text-gray-800 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Dịch vụ của tôi</p>
      <p class="text-gray-500 dark:text-gray-400 text-base font-normal leading-normal">Xem lại lịch sử các dịch vụ bạn đã đặt.</p>
    </div>
  </div>
  <!-- Search and Filter Bar -->
  <div class="flex flex-col md:flex-row gap-4 p-4">
    <!-- SearchBar -->
    <div class="flex-grow">
      <label class="flex flex-col min-w-40 h-12 w-full">
        <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
          <div class="text-gray-500 dark:text-gray-400 flex border-none bg-white dark:bg-background-dark/50 items-center justify-center pl-4 rounded-l-lg border-r-0">
            <span class="material-symbols-outlined">search</span>
          </div>
          <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-800 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border-none bg-white dark:bg-background-dark/50 h-full placeholder:text-gray-500 dark:placeholder:text-gray-400 px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal" placeholder="Tìm theo tên dịch vụ hoặc mã..." value=""/>
        </div>
      </label>
    </div>
    <!-- Chips -->
    <div class="flex gap-3 items-center flex-wrap">
      <button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-white dark:bg-background-dark/50 px-4">
        <p class="text-gray-800 dark:text-gray-300 text-sm font-medium leading-normal">Tuần này</p>
        <span class="material-symbols-outlined text-gray-600 dark:text-gray-400 text-base">expand_more</span>
      </button>
      <button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-white dark:bg-background-dark/50 px-4">
        <p class="text-gray-800 dark:text-gray-300 text-sm font-medium leading-normal">Tháng này</p>
        <span class="material-symbols-outlined text-gray-600 dark:text-gray-400 text-base">expand_more</span>
      </button>
      <button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-white dark:bg-background-dark/50 px-4">
        <p class="text-gray-800 dark:text-gray-300 text-sm font-medium leading-normal">Toàn bộ</p>
        <span class="material-symbols-outlined text-gray-600 dark:text-gray-400 text-base">expand_more</span>
      </button>
    </div>
  </div>
  <!-- SegmentedButtons -->
  <div class="flex px-4">
    <div class="flex h-10 flex-1 items-center justify-center rounded-lg bg-background-light dark:bg-background-dark/30 p-1">
      <label class="flex cursor-pointer h-full grow items-center justify-center overflow-hidden rounded-lg px-2 has-[:checked]:bg-white dark:has-[:checked]:bg-background-dark/80 has-[:checked]:shadow-[0_1px_3px_rgba(0,0,0,0.1)] has-[:checked]:text-gray-800 dark:has-[:checked]:text-white text-gray-500 dark:text-gray-400 text-sm font-medium leading-normal">
        <span class="truncate">Sắp tới</span>
        <input checked class="invisible w-0" name="status-filter" type="radio" value="Sắp tới"/>
      </label>
      <label class="flex cursor-pointer h-full grow items-center justify-center overflow-hidden rounded-lg px-2 has-[:checked]:bg-white dark:has-[:checked]:bg-background-dark/80 has-[:checked]:shadow-[0_1px_3px_rgba(0,0,0,0.1)] has-[:checked]:text-gray-800 dark:has-[:checked]:text-white text-gray-500 dark:text-gray-400 text-sm font-medium leading-normal">
        <span class="truncate">Đã sử dụng</span>
        <input class="invisible w-0" name="status-filter" type="radio" value="Đã sử dụng"/>
      </label>
      <label class="flex cursor-pointer h-full grow items-center justify-center overflow-hidden rounded-lg px-2 has-[:checked]:bg-white dark:has-[:checked]:bg-background-dark/80 has-[:checked]:shadow-[0_1px_3px_rgba(0,0,0,0.1)] has-[:checked]:text-gray-800 dark:has-[:checked]:text-white text-gray-500 dark:text-gray-400 text-sm font-medium leading-normal">
        <span class="truncate">Đã hủy</span>
        <input class="invisible w-0" name="status-filter" type="radio" value="Đã hủy"/>
      </label>
    </div>
  </div>
  <!-- Service List -->
  <div class="flex flex-col gap-4 p-4">
    <?php if (empty($services)): ?>
      <div class="flex flex-col items-center justify-center text-center p-16 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl mt-8">
        <div class="text-primary mb-4">
            <span class="material-symbols-outlined" style="font-size: 48px;">sentiment_dissatisfied</span>
        </div>
        <p class="text-xl font-bold text-gray-800 dark:text-white">Không tìm thấy dịch vụ nào</p>
        <p class="text-gray-500 dark:text-gray-400 mt-2">Bạn chưa đặt dịch vụ nào. Hãy khám phá và đặt ngay!</p>
        <a href="../customer/index.php" class="mt-6 inline-flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em]">Khám phá dịch vụ</a>
      </div>
    <?php else: ?>
      <?php foreach ($services as $s): ?>
        <div class="flex flex-col md:flex-row items-center gap-4 rounded-xl bg-white dark:bg-background-dark/50 p-4 shadow-sm transition-shadow hover:shadow-md">
          <div class="flex-grow w-full">
            <h3 class="font-bold text-lg text-gray-800 dark:text-white"><?php echo htmlspecialchars($s['service_name']); ?></h3>
            <div class="flex flex-wrap gap-x-6 gap-y-2 mt-2 text-sm text-gray-500 dark:text-gray-400">
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-base">calendar_today</span>
                <span><?php echo htmlspecialchars($s['description'] ?: '—'); ?></span>
              </div>
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-base">receipt_long</span>
                <span class="font-mono">SV-<?php echo htmlspecialchars($s['service_id']); ?></span>
              </div>
            </div>
          </div>
          <div class="flex items-center gap-4 w-full md:w-auto">
            <p class="text-lg font-bold text-gray-800 dark:text-white whitespace-nowrap"><?php echo number_format($s['price'], 0, ',', '.'); ?> VNĐ</p>
            <a href="../customer/booking.php?service_id=<?php echo urlencode($s['service_id']); ?>&font=<?php echo urlencode('Be Vietnam Pro'); ?>" class="flex h-10 items-center justify-center gap-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-background-dark/50 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">Xem chi tiết</a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
  <!-- Pagination -->
  <div class="flex justify-center p-4 mt-4">
    <nav class="flex items-center gap-2">
      <button class="flex items-center justify-center size-9 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 disabled:opacity-50" disabled>
        <span class="material-symbols-outlined text-lg">chevron_left</span>
      </button>
      <button class="flex items-center justify-center size-9 rounded-lg bg-primary text-white text-sm font-bold">1</button>
      <button class="flex items-center justify-center size-9 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 text-sm">2</button>
      <button class="flex items-center justify-center size-9 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 text-sm">3</button>
      <span class="text-gray-500">...</span>
      <button class="flex items-center justify-center size-9 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 text-sm">10</button>
      <button class="flex items-center justify-center size-9 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700">
        <span class="material-symbols-outlined text-lg">chevron_right</span>
      </button>
    </nav>
  </div>
  
</main>
</div>
</div>
</div>
</div>
</body></html>
