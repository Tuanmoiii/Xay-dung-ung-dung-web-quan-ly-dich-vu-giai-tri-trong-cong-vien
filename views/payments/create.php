<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
require_once __DIR__ . '/../../functions/bookings_functions.php';
checkLogin();

$ref = trim($_GET['ref'] ?? '');
if (!$ref) {
    $_SESSION['error'] = 'Mã đặt vé không hợp lệ';
    header('Location: list.php');
    exit();
}

$booking = getBookingByRef($ref);
if (!$booking) {
    $_SESSION['error'] = 'Không tìm thấy đặt vé';
    header('Location: list.php');
    exit();
}

?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Thanh toán - <?php echo htmlspecialchars($booking['booking_ref']); ?></title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600;800&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
  <script>
    tailwind.config = { theme: { extend: { colors: { primary: '#2563eb' }, fontFamily: { display: ['Be Vietnam Pro', 'sans-serif'] } } } };
  </script>
  <style>.material-symbols-outlined{font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24}</style>
</head>
<body class="font-display bg-background-light">
  <div class="flex h-screen w-full">
    <aside class="flex w-64 flex-col bg-white p-4 text-slate-800 shadow-lg">
      <div class="flex flex-col gap-4">
        <div class="flex items-center gap-3 px-3">
          <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCpZvXhTGHB9Juv75zKOh0hhux9SIEV-c9ptxDZ8f46jELYu0vy0FpxuzxlK_DOEihB04DR9h8VbZYlbXmK7daqIskHdadTHLA2NV1gSwjGVcTRXz7hMEl8kBy783saHdBMcfZ-fvfnVCFZ7GJY1Jk1SMkxWmggd6U0Rf4_YhutEPYk35-NEaFd14PoOmGCUKsHE3vwgrqWrAiOUDUYbmSSl2TJIGSME123hS-TTVIzalAyzlQNgRv4ioOUR0eMZrLMxW7q34WQmcfz");'></div>
          <div class="flex flex-col"><h1 class="text-slate-800 text-base font-bold">ParkAdmin</h1><p class="text-slate-500 text-sm">Quản lý Dịch vụ</p></div>
        </div>
        <nav class="mt-4 flex flex-col gap-2">
          <a class="flex items-center gap-3 rounded-lg bg-primary-light px-3 py-2 text-primary-dark font-medium" href="../dashboard/index.php"><span class="material-symbols-outlined">dashboard</span><p class="text-sm">Tổng quan</p></a>
          <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-600 hover:bg-primary-light hover:text-primary-dark" href="../services/list.php"><span class="material-symbols-outlined">local_activity</span><p class="text-sm">Quản lý Dịch vụ</p></a>
          <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-600 hover:bg-primary-light hover:text-primary-dark" href="../schedules/list.php"><span class="material-symbols-outlined">calendar_month</span><p class="text-sm">Quản lý Lịch chiếu</p></a>
          <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-600 hover:bg-primary-light hover:text-primary-dark" href="../customers/list.php"><span class="material-symbols-outlined">group</span><p class="text-sm">Quản lý Khách hàng</p></a>
          <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-600 hover:bg-primary-light hover:text-primary-dark" href="../bookings/history.php"><span class="material-symbols-outlined">confirmation_number</span><p class="text-sm">Quản lý Đặt vé</p></a>
          <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-600 hover:bg-primary-light hover:text-primary-dark" href="list.php"><span class="material-symbols-outlined">credit_card</span><p class="text-sm">Quản lý Thanh toán</p></a>
        </nav>
      </div>
      <div class="mt-auto">
        <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-600 hover:bg-primary-light hover:text-primary-dark" href="../../handle/logout_process.php"><span class="material-symbols-outlined">logout</span><p class="text-sm">Đăng xuất</p></a>
      </div>
    </aside>

    <main class="flex-1 p-8">
      <div class="mx-auto max-w-3xl">
        <div class="flex items-center justify-between mb-6">
          <h1 class="text-2xl font-semibold">Thanh toán — <?php echo htmlspecialchars($booking['booking_ref']); ?></h1>
          <a href="../bookings/history.php" class="px-3 py-2 rounded bg-slate-100">Quay lại</a>
        </div>

        <?php if (isset($_SESSION['error'])): ?><div class="mb-4 rounded-md bg-rose-50 p-3 text-rose-700"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div><?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?><div class="mb-4 rounded-md bg-emerald-50 p-3 text-emerald-700"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div><?php endif; ?>

        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
          <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
              <h2 class="text-lg font-semibold mb-3">Khách hàng</h2>
              <p class="text-sm text-slate-700"><?php echo htmlspecialchars($booking['full_name']); ?></p>
              <p class="text-sm text-slate-500 mt-2">Mã: <?php echo htmlspecialchars($booking['booking_ref']); ?></p>
              <p class="text-sm text-slate-500">Ngày: <?php echo htmlspecialchars($booking['date'] ?? ''); ?> <?php echo htmlspecialchars($booking['start_time'] ?? ''); ?></p>
            </div>
            <div>
              <h2 class="text-lg font-semibold mb-3">Thanh toán</h2>
              <form method="post" action="../../handle/payments_process.php">
                <input type="hidden" name="booking_ref" value="<?php echo htmlspecialchars($booking['booking_ref']); ?>">
                <div class="mb-3">
                  <label class="block text-sm font-medium text-slate-700">Số tiền</label>
                  <div class="mt-1 text-lg font-bold">₫<?php echo number_format((float)$booking['total_amount']); ?></div>
                </div>
                <div class="mb-3">
                  <label class="block text-sm font-medium text-slate-700">Phương thức thanh toán</label>
                  <select name="method" class="mt-1 block w-full rounded-md border-gray-200 bg-white py-2 px-3 text-sm">
                    <option value="momo">Momo</option>
                    <option value="visa">Visa</option>
                    <option value="zalopay">ZaloPay</option>
                    <option value="cash">Tiền mặt</option>
                  </select>
                </div>
                <div class="flex gap-3 mt-4">
                  <button type="submit" name="create" class="px-4 py-2 rounded-md bg-emerald-600 text-white">Xác nhận thanh toán</button>
                  <a href="../bookings/history.php" class="px-4 py-2 rounded-md bg-slate-100">Hủy</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
