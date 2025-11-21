<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
require_once __DIR__ . '/../../functions/bookings_functions.php';
checkLogin();

$ref = trim($_GET['ref'] ?? '');
if (!$ref) {
    $_SESSION['error'] = 'Mã đặt vé không hợp lệ';
    header('Location: history.php');
    exit();
}

$booking = getBookingByRef($ref);
if (!$booking) {
    $_SESSION['error'] = 'Không tìm thấy đặt vé';
    header('Location: history.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Chi tiết đặt vé</title>
            <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
            <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600;800&display=swap" rel="stylesheet">
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
                :root { font-family: 'Be Vietnam Pro', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
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
                        <a class="flex items-center gap-3 rounded-lg bg-primary-light px-3 py-2 text-primary-dark font-medium" href="#">
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
                        <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-600 hover:bg-primary-light hover:text-primary-dark" href="history.php">
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
                <div class="mx-auto max-w-4xl">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-semibold">Chi tiết đặt vé</h1>
                        <a href="history.php" class="px-4 py-2 bg-slate-100 rounded-md">Quay lại</a>
                    </div>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="mb-4 rounded-md bg-rose-50 p-3 text-rose-700"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="mb-4 rounded-md bg-emerald-50 p-3 text-emerald-700"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                    <?php endif; ?>

                    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <h2 class="text-lg font-semibold mb-3">Thông tin đặt vé</h2>
                                <dl class="grid grid-cols-1 gap-2 text-sm text-slate-700">
                                    <div><dt class="font-medium">Mã đặt vé</dt><dd><?php echo htmlspecialchars($booking['booking_ref']); ?></dd></div>
                                    <div><dt class="font-medium">Trạng thái</dt>
                                        <dd>
                                            <?php $st = strtolower($booking['status'] ?? 'pending');
                                                if ($st === 'paid' || $st === 'đã thanh toán'): ?>
                                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800">Đã thanh toán</span>
                                                <?php elseif ($st === 'pending' || $st === 'chờ thanh toán'): ?>
                                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800">Chờ thanh toán</span>
                                                <?php elseif ($st === 'cancelled' || $st === 'đã hủy'): ?>
                                                    <span class="inline-flex items-center rounded-full bg-rose-100 px-2 py-0.5 text-xs font-medium text-rose-800">Đã hủy</span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-800"><?php echo htmlspecialchars($booking['status']); ?></span>
                                                <?php endif; ?>
                                        </dd>
                                    </div>
                                    <div><dt class="font-medium">Khách hàng</dt><dd><?php echo htmlspecialchars($booking['full_name']); ?></dd></div>
                                    <div><dt class="font-medium">Số người</dt><dd><?php echo (int)$booking['num_people']; ?></dd></div>
                                    <div><dt class="font-medium">Tổng tiền</dt><dd>₫<?php echo number_format((float)$booking['total_amount']); ?></dd></div>
                                </dl>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold mb-3">Thông tin dịch vụ</h2>
                                <dl class="grid grid-cols-1 gap-2 text-sm text-slate-700">
                                    <div><dt class="font-medium">Dịch vụ</dt><dd><?php echo htmlspecialchars($booking['service_name'] ?? '—'); ?></dd></div>
                                    <div><dt class="font-medium">Ngày</dt><dd><?php echo htmlspecialchars($booking['date'] ?? '—'); ?></dd></div>
                                    <div><dt class="font-medium">Giờ</dt><dd><?php echo htmlspecialchars(($booking['start_time'] ?? '') . ' - ' . ($booking['end_time'] ?? '')); ?></dd></div>
                                    <div><dt class="font-medium">Giá vé</dt><dd>₫<?php echo number_format((float)($booking['price'] ?? 0)); ?> / người</dd></div>
                                </dl>
                            </div>
                        </div>

                        <?php if (strtolower($booking['status'] ?? '') === 'pending' || strtolower($booking['status'] ?? '') === 'chờ thanh toán'): ?>
                            <div class="mt-6 flex gap-3">
                                <form action="../../handle/bookings_process.php" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đặt vé này?');">
                                    <input type="hidden" name="action" value="cancel">
                                    <input type="hidden" name="booking_ref" value="<?php echo htmlspecialchars($booking['booking_ref']); ?>">
                                    <button type="submit" class="px-4 py-2 rounded-md bg-rose-500 text-white">Hủy đặt vé</button>
                                </form>
                                <a href="../payments/create.php?ref=<?php echo urlencode($booking['booking_ref']); ?>" class="px-4 py-2 rounded-md bg-emerald-600 text-white">Thanh toán</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </body>
    </html>
