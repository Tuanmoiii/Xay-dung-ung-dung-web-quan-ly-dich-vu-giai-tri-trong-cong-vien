<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
require_once __DIR__ . '/../../functions/services_functions.php';
require_once __DIR__ . '/../../functions/schedules_functions.php';
require_once __DIR__ . '/../../functions/bookings_functions.php';

// ✅ Kiểm tra đăng nhập
checkLogin();

// ✅ Kiểm tra quyền (role_id = 4 là Customer)
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 4) {
    $_SESSION['error'] = 'Bạn không có quyền truy cập trang khách hàng!';
    header('Location: ../dashboard/index.php');
    exit();
}

// ✅ Lấy thông tin người dùng hiện tại
$user = getCurrentUser();
$customer_id = $user['id'];
$services = getAllServices();

// --- Simple server-side search/filter (tên dịch vụ + giá) ---
$q = trim($_GET['q'] ?? '');
// Use isset() to avoid undefined index notices when the page is loaded without query params
$price_min = isset($_GET['price_min']) && $_GET['price_min'] !== '' ? floatval($_GET['price_min']) : null;
$price_max = isset($_GET['price_max']) && $_GET['price_max'] !== '' ? floatval($_GET['price_max']) : null;

$filtered_services = array_filter($services, function($s) use ($q, $price_min, $price_max) {
    // filter by name
    if ($q !== '') {
        if (stripos($s['service_name'], $q) === false) return false;
    }
    // filter by price min
    if (!is_null($price_min) && isset($s['price'])) {
        if (floatval($s['price']) < $price_min) return false;
    }
    // filter by price max
    if (!is_null($price_max) && isset($s['price'])) {
        if (floatval($s['price']) > $price_max) return false;
    }
    return true;
});
?>

<!DOCTYPE html>
<html class="" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Trang Cá Nhân - Quản lý Dịch vụ</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24 }
    </style>
    <script>
        tailwind.config = { darkMode: "class", theme: { extend: { colors: { "primary": "#135bec", "background-light": "#f6f6f8", "background-dark": "#101622" }, fontFamily: { "display": ["Be Vietnam Pro", "sans-serif"] } } } };
    </script>
    <style>body{font-family:Be Vietnam Pro, sans-serif}</style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display">
<div class="relative flex w-full min-h-screen">
    <!-- SideNavBar -->
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
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/30" href="#">
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
    <main class="flex-1 p-8 overflow-y-auto">
        <div class="mx-auto max-w-7xl">
            <!-- PageHeading -->
            <div class="flex flex-wrap justify-between gap-3 mb-6">
                <div class="flex min-w-72 flex-col gap-2">
                    <p class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Chào mừng trở lại, <?php echo htmlspecialchars($user['full_name'] ?? $user['name'] ?? $user['username'] ?? 'Bạn'); ?>!</p>
                    <p class="text-gray-500 dark:text-[#92a4c9] text-base font-normal leading-normal">Cùng khám phá những dịch vụ tuyệt vời đang chờ bạn.</p>
                </div>
                <a href="../services/list.php" class="px-5 py-2.5 h-fit bg-primary text-white font-medium rounded-lg text-sm shadow-sm hover:bg-primary/90 transition-colors">Mua thêm dịch vụ</a>
            </div>

            <!-- Alerts -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="mb-4 rounded-md bg-emerald-50 p-3 text-emerald-700"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php elseif (isset($_SESSION['error'])): ?>
                <div class="mb-4 rounded-md bg-rose-50 p-3 text-rose-700"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <?php
                    $bookings_list = function_exists('getBookingsByCustomer') ? getBookingsByCustomer($customer_id) : [];
                    $totalSpent = 0;
                    if (!empty($bookings_list)) {
                        $totalSpent = array_sum(array_column($bookings_list, 'total'));
                    }
                    $usedCount = is_array($bookings_list) ? count($bookings_list) : 0;
                    $points = intval($totalSpent / 1000);
                ?>
                <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-[#111722] border border-gray-200 dark:border-[#324467]">
                    <p class="text-gray-600 dark:text-white text-base font-medium leading-normal">Tổng chi tiêu</p>
                    <p class="text-gray-900 dark:text-white tracking-light text-3xl font-bold leading-tight"><?php echo number_format($totalSpent ?? 0, 0, ',', '.'); ?>₫</p>
                </div>
                <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-[#111722] border border-gray-200 dark:border-[#324467]">
                    <p class="text-gray-600 dark:text-white text-base font-medium leading-normal">Số dịch vụ đã sử dụng</p>
                    <p class="text-gray-900 dark:text-white tracking-light text-3xl font-bold leading-tight"><?php echo intval($usedCount ?? 0); ?></p>
                </div>
                <div class="flex flex-col gap-2 rounded-xl p-6 bg-white dark:bg-[#111722] border border-gray-200 dark:border-[#324467]">
                    <p class="text-gray-600 dark:text-white text-base font-medium leading-normal">Điểm thưởng</p>
                    <p class="text-gray-900 dark:text-white tracking-light text-3xl font-bold leading-tight"><?php echo intval($points ?? 0); ?></p>
                </div>
            </div>

            <!-- Service List Section -->
            <div class="flex flex-col gap-4">
                <!-- SectionHeader & ToolBar -->
                <div class="flex justify-between items-center">
                    <h2 class="text-gray-900 dark:text-white text-2xl font-bold leading-tight tracking-[-0.015em]">Dịch vụ của tôi</h2>
                    <div class="flex gap-2">
                        <form method="GET" class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">search</span>
                            <input name="q" value="<?php echo htmlspecialchars($q); ?>" class="w-full sm:w-64 pl-10 pr-4 py-2 bg-white dark:bg-[#111722] border border-gray-200 dark:border-[#324467] rounded-lg text-sm text-gray-900 dark:text-white focus:ring-primary focus:border-primary" placeholder="Tìm kiếm dịch vụ..." type="text"/>
                        </form>
                        <button class="p-2.5 text-gray-600 dark:text-white border border-gray-200 dark:border-[#324467] rounded-lg hover:bg-gray-100 dark:hover:bg-white/10">
                            <span class="material-symbols-outlined text-base">filter_list</span>
                        </button>
                    </div>
                </div>

                <!-- Price filters (visible on md+) -->
                <form method="GET" class="flex gap-2 items-end">
                    <input type="hidden" name="q" value="<?php echo htmlspecialchars($q); ?>">
                    <div class="flex items-end gap-2">
                        <div class="flex flex-col text-sm">
                            <label class="text-gray-600 dark:text-gray-300">Giá từ (VNĐ)</label>
                            <input name="price_min" type="number" step="1000" min="0" value="<?php echo htmlspecialchars($price_min ?? ''); ?>" class="mt-1 w-40 rounded-md border-gray-200 bg-white dark:bg-[#111722] py-2 px-3 text-sm"/>
                        </div>
                        <div class="flex flex-col text-sm">
                            <label class="text-gray-600 dark:text-gray-300">Giá đến (VNĐ)</label>
                            <input name="price_max" type="number" step="1000" min="0" value="<?php echo htmlspecialchars($price_max ?? ''); ?>" class="mt-1 w-40 rounded-md border-gray-200 bg-white dark:bg-[#111722] py-2 px-3 text-sm"/>
                        </div>
                        <div class="flex items-center">
                            <button type="submit" class="px-3 py-2 rounded-md bg-primary text-white">Tìm</button>
                        </div>
                    </div>
                </form>

                <!-- Service/Ticket Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if (empty($filtered_services)): ?>
                        <div class="col-span-full text-center text-gray-500 py-8">Không có dịch vụ phù hợp với tìm kiếm.</div>
                    <?php else: ?>
                        <?php foreach ($filtered_services as $service):
                            $schedules = getSchedulesByServiceId($service['service_id']);
                        ?>
                        <div class="flex flex-col gap-4 p-5 rounded-xl bg-white dark:bg-[#111722] border border-gray-200 dark:border-[#324467]">
                            <div class="flex items-start justify-between">
                                <div class="flex flex-col">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white"><?php echo htmlspecialchars($service['service_name']); ?></h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Ngày cập nhật: <?php echo htmlspecialchars($service['created_at'] ?? $service['date'] ?? ''); ?></p>
                                </div>
                                <span class="text-sm font-semibold text-green-500 bg-green-500/10 px-2.5 py-1 rounded-full"><?php echo (isset($service['active']) && $service['active']) ? 'Đang bán' : 'Tạm ngưng'; ?></span>
                            </div>
                            <div class="w-full p-4 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center">
                                    <?php
                                        // Decide image source server-side to avoid client-side flicker.
                                        $imgField = $service['image'] ?? '';
                                        if ($imgField) {
                                            if (preg_match('~^https?://~i', $imgField)) {
                                                $imgSrc = $imgField;
                                            } else {
                                                $candidate = __DIR__ . '/../../images/services/' . $imgField;
                                                if (file_exists($candidate)) {
                                                    $imgSrc = '../../images/services/' . $imgField;
                                                } else {
                                                    $imgSrc = '../../images/services/placeholder.jpg';
                                                }
                                            }
                                        } else {
                                            $imgSrc = '../../images/services/placeholder.jpg';
                                        }
                                    ?>
                                    <img class="w-32 h-32 object-cover" alt="Ảnh dịch vụ" src="<?php echo htmlspecialchars($imgSrc); ?>" />
                                    <p class="text-xs text-gray-500 mt-2">Ảnh nguồn: <a class="underline" href="<?php echo htmlspecialchars($imgSrc); ?>" target="_blank"><?php echo htmlspecialchars($imgField ?: 'placeholder.jpg'); ?></a></p>
                            </div>
                            <div class="flex flex-col">
                                <p class="text-sm text-gray-600 dark:text-gray-300">Tổng tiền</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo number_format($service['price'] ?? 0, 0, ',', '.'); ?>₫</p>
                            </div>

                            <?php if (!empty($schedules)): ?>
                                <form method="POST" action="../../handle/bookings_process.php" class="mt-auto">
                                    <div class="mb-2">
                                        <label class="block text-sm font-medium text-slate-700">Chọn lịch chiếu</label>
                                        <select name="schedule_id" class="mt-1 block w-full rounded-md border-gray-200 bg-white py-2 px-3 text-sm" required>
                                            <?php foreach ($schedules as $sch): ?>
                                                <option value="<?php echo $sch['schedule_id']; ?>"><?php echo htmlspecialchars($sch['date']); ?> - <?php echo htmlspecialchars($sch['start_time']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="flex items-center gap-2 mb-3">
                                        <label class="mb-0">Số người</label>
                                        <input type="number" name="num_people" value="1" min="1" class="w-20 rounded-md border-gray-200 py-1 px-2" required>
                                    </div>

                                    <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($customer_id); ?>">
                                    <input type="hidden" name="service_id" value="<?php echo htmlspecialchars($service['service_id']); ?>">
                                    <button type="submit" name="create" class="btn w-full text-center py-2.5 bg-primary text-white font-medium text-sm rounded-lg">Đặt vé</button>
                                </form>
                            <?php else: ?>
                                <div class="mt-auto text-muted">Không có lịch chiếu khả dụng.</div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
