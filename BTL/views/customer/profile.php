<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
require_once __DIR__ . '/../../functions/customers_functions.php';
require_once __DIR__ . '/../../functions/bookings_functions.php';

checkLogin();

$user = getCurrentUser();
$user_id = $user['id'] ?? 0;

// Try to find a customer record linked to this user
$customer = null;
if (function_exists('getCustomerByUserId')) {
    $customer = getCustomerByUserId($user_id);
}
if (!$customer && function_exists('getCustomerById')) {
    $customer = getCustomerById($user_id);
}

// If still no customer, prepare empty defaults
if (!$customer) {
    $customer = [
        'customer_id' => 0,
        'full_name' => $user['full_name'] ?? $user['name'] ?? '',
        'email' => $user['email'] ?? '',
        'phone' => $user['phone'] ?? '',
    ];
}
?>
<!DOCTYPE html>
<html class="light" lang="vi">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Thông tin cá nhân</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..200" rel="stylesheet"/>
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
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    }
                }
            }
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
<body class="bg-background-light dark:bg-background-dark font-display">
<div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
<div class="layout-container flex h-full grow flex-row">
<!-- SideNavBar (match customer index menu) -->
<aside class="sticky top-0 h-screen w-64 flex-shrink-0 bg-[#111722] p-4 flex flex-col justify-between">
    <div class="flex flex-col gap-8">
        <div class="flex items-center gap-3 px-2">
            <span class="material-symbols-outlined text-primary text-3xl">local_activity</span>
            <p class="text-white text-xl font-bold">VuiChơi Park</p>
        </div>
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="Avatar" style='background-image: url("<?php echo 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($customer['email'] ?? ''))) . '?d=identicon'; ?>");'></div>
                <div class="flex flex-col">
                    <h1 class="text-white text-base font-medium leading-normal"><?php echo htmlspecialchars($user['full_name'] ?? $user['name'] ?? $user['username'] ?? 'Khách'); ?></h1>
                    <p class="text-[#92a4c9] text-sm font-normal leading-normal">Thành viên</p>
                </div>
            </div>
            <nav class="flex flex-col gap-2">
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/30" href="../customer/index.php">
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
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors" href="../customer/services.php">
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
<!-- Main Content -->
<main class="flex-1 p-6 lg:p-10">
  <div class="mx-auto max-w-7xl">
    <!-- PageHeading -->
    <div class="flex flex-wrap justify-between gap-3 mb-8">
      <div class="flex min-w-72 flex-col gap-2">
        <p class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Hồ sơ của bạn</p>
        <p class="text-gray-500 dark:text-gray-400 text-base font-normal leading-normal">Quản lý thông tin cá nhân và xem lại hoạt động của bạn.</p>
      </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Left Column: Profile Form -->
      <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-900/50 rounded-xl shadow-sm p-6">
          <!-- ProfileHeader -->
          <div class="flex flex-col gap-4 @container sm:flex-row sm:justify-between sm:items-center pb-6 border-b border-gray-200 dark:border-gray-800">
            <div class="flex gap-4 items-center">
              <div class="relative">
                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full min-h-24 w-24" data-alt="User avatar image" style='background-image: url("https://www.gravatar.com/avatar/" + <?php echo json_encode(md5(strtolower(trim($customer['email'] ?? '')))); ?> + "?d=identicon");'></div>
                <button class="absolute bottom-0 right-0 flex items-center justify-center size-8 bg-white dark:bg-gray-800 rounded-full border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700">
                  <span class="material-symbols-outlined text-base text-gray-600 dark:text-gray-300">photo_camera</span>
                </button>
              </div>
              <div class="flex flex-col justify-center">
                <p class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em]"><?php echo htmlspecialchars($customer['full_name'] ?: ($user['full_name'] ?? 'Người dùng')); ?></p>
                <p class="text-gray-500 dark:text-gray-400 text-base font-normal leading-normal"><?php echo htmlspecialchars($customer['email'] ?? ($user['email'] ?? '')); ?></p>
                <p class="text-gray-500 dark:text-gray-400 text-base font-normal leading-normal">Tham gia</p>
              </div>
            </div>
            <div class="flex w-full gap-3 sm:w-auto">
              <a href="#" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-sm font-bold leading-normal tracking-[0.015em] flex-1 sm:flex-auto hover:bg-gray-200 dark:hover:bg-gray-700">Đổi mật khẩu</a>
            </div>
          </div>
          <!-- Form Fields -->
          <?php if (isset($_SESSION['success'])): ?>
            <div class="mt-4 mb-4 rounded bg-emerald-50 p-3 text-emerald-700"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
          <?php elseif (isset($_SESSION['error'])): ?>
            <div class="mt-4 mb-4 rounded bg-rose-50 p-3 text-rose-700"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
          <?php endif; ?>

          <form method="post" action="../../handle/customer_profile_process.php" class="mt-6 space-y-6">
            <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($customer['customer_id'] ?? 0); ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <label class="flex flex-col">
                <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">Họ và tên</p>
                <input name="full_name" required class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 h-12 placeholder:text-gray-500 p-[15px] text-base font-normal leading-normal" value="<?php echo htmlspecialchars($customer['full_name'] ?? ''); ?>" />
              </label>
              <label class="flex flex-col">
                <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">Ngày sinh</p>
                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 h-12 placeholder:text-gray-500 p-[15px] text-base font-normal leading-normal" type="date" value="" />
              </label>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <label class="flex flex-col">
                <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">Số điện thoại</p>
                <input name="phone" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 h-12 placeholder:text-gray-500 p-[15px] text-base font-normal leading-normal" value="<?php echo htmlspecialchars($customer['phone'] ?? ''); ?>" />
              </label>
              <label class="flex flex-col">
                <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">Email</p>
                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-400 dark:text-gray-500 focus:outline-0 ring-0 border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800/50 h-12 p-[15px] text-base font-normal leading-normal cursor-not-allowed" disabled value="<?php echo htmlspecialchars($customer['email'] ?? ($user['email'] ?? '')); ?>" />
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($customer['email'] ?? ($user['email'] ?? '')); ?>" />
              </label>
            </div>
            <div>
              <label class="flex flex-col">
                <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">Địa chỉ</p>
                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 h-12 placeholder:text-gray-500 p-[15px] text-base font-normal leading-normal" placeholder="Nhập địa chỉ của bạn" />
              </label>
            </div>
            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4">
              <a href="index.php" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-200 dark:hover:bg-gray-700">Hủy</a>
              <button type="submit" name="update" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">Lưu thay đổi</button>
            </div>
          </form>
        </div>
      </div>
      <!-- Right Column: Activity -->
      <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-900/50 rounded-xl shadow-sm p-6">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Hoạt động gần đây</h3>
          <div class="space-y-4">
            <div class="flex items-start gap-4">
              <div class="flex items-center justify-center size-10 rounded-full bg-primary/10 dark:bg-primary/20">
                <span class="material-symbols-outlined text-primary">confirmation_number</span>
              </div>
              <div>
                <p class="font-medium text-gray-800 dark:text-gray-200">Đặt vé Vòng quay Mặt trời</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Hôm nay, 10:30 AM</p>
              </div>
              <p class="text-sm font-semibold text-green-600 dark:text-green-400 ml-auto">Đã xác nhận</p>
            </div>
            <a class="mt-6 flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-200 dark:hover:bg-gray-700" href="../customer/history.php">Xem tất cả hoạt động</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
</div>
</div>
</body>
</html>
