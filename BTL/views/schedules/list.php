<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
checkLogin();
require_once __DIR__ . '/../../functions/services_functions.php';
require_once __DIR__ . '/../../functions/schedules_functions.php';

$services = getAllServices();

$service_id = intval($_GET['service_id'] ?? 0);
$schedules = [];
if ($service_id > 0) {
	$schedules = getSchedulesByService($service_id);
}
?>
<!DOCTYPE html>
<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Quản lý Lịch chiếu</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
							"display": ["Be Vietnam Pro"]
						},
						borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
					},
				},
			}
		</script>
</head>
<body class="font-display bg-background-light dark:bg-background-dark">
<div class="relative flex min-h-screen w-full flex-row">
<!-- SideNavBar (standardized to match dashboard) -->
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
<main class="flex-1 overflow-y-auto p-8">
	<div class="mx-auto max-w-7xl">
		<!-- PageHeading -->
		<div class="flex flex-wrap items-center justify-between gap-4">
			<div class="flex flex-col gap-1">
				<h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Quản lý Lịch chiếu</h1>
				<p class="text-slate-600 dark:text-slate-400">Xem và điều chỉnh lịch hoạt động cho tất cả các dịch vụ giải trí.</p>
			</div>
			<a href="create.php" class="flex h-10 cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg bg-primary px-4 text-white shadow-md transition-all hover:bg-primary/90">
				<span class="material-symbols-outlined text-lg">add</span>
				<span class="truncate text-sm font-bold">Thêm Lịch chiếu mới</span>
			</a>
		</div>

		<!-- Toolbar & SegmentedButtons -->
		<div class="mt-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
			<div class="flex items-center gap-2">
				<div class="relative w-full max-w-xs">
					<span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
					<input class="w-full rounded-lg border-slate-300 bg-white py-2 pl-10 pr-4 text-sm text-slate-800 placeholder-slate-400 focus:border-primary focus:ring-primary dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:placeholder-slate-500" placeholder="Tìm kiếm dịch vụ..." type="text"/>
				</div>
				<a class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-300 bg-white text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800" href="#">
					<span class="material-symbols-outlined text-xl">filter_list</span>
				</a>
			</div>
			<div class="flex h-10 w-full max-w-xs items-center justify-center rounded-lg bg-slate-200 p-1 dark:bg-slate-800">
				<form method="get" class="flex w-full">
					<select name="service_id" class="grow rounded-l-lg border border-slate-300 bg-white px-3 text-sm text-slate-800 focus:border-primary">
						<option value="0">-- Chọn dịch vụ --</option>
						<?php foreach ($services as $sv): ?>
							<option value="<?= $sv['service_id'] ?>" <?= $service_id == $sv['service_id'] ? 'selected' : '' ?>><?= htmlspecialchars($sv['service_name']) ?> (<?= htmlspecialchars($sv['code']) ?>)</option>
						<?php endforeach; ?>
					</select>
					<button type="submit" class="h-9 rounded-r-lg bg-primary px-3 text-white">Xem</button>
				</form>
			</div>
		</div>

		<!-- Table -->
		<div class="mt-6 flow-root">
			<div class="overflow-x-auto">
				<div class="inline-block min-w-full align-middle">
					<?php if (isset($_SESSION['error'])): ?>
						<div class="mb-4 rounded-md bg-red-50 p-3 text-red-800"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
					<?php endif; ?>
					<?php if (isset($_SESSION['success'])): ?>
						<div class="mb-4 rounded-md bg-green-50 p-3 text-green-800"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
					<?php endif; ?>

					<table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
						<thead>
							<tr>
								<th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-slate-900 dark:text-white sm:pl-0">Tên Dịch vụ</th>
								<th class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900 dark:text-white">Thời gian bắt đầu</th>
								<th class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900 dark:text-white">Thời gian kết thúc</th>
								<th class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900 dark:text-white">Trạng thái</th>
								<th class="relative py-3.5 pl-3 pr-4 sm:pr-0" scope="col"><span class="sr-only">Hành động</span></th>
							</tr>
						</thead>
						<tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-800 dark:bg-background-dark">
							<?php if ($service_id <= 0): ?>
								<tr><td colspan="5" class="text-center py-6">Vui lòng chọn một dịch vụ để xem lịch.</td></tr>
							<?php else: if (!empty($schedules)): foreach ($schedules as $i => $sch): ?>
								<tr>
									<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-slate-900 dark:text-white sm:pl-0"><?= htmlspecialchars($sch['service_name'] ?? '') ?></td>
									<td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500 dark:text-slate-400"><?= htmlspecialchars($sch['start_time']) ?></td>
									<td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500 dark:text-slate-400"><?= htmlspecialchars($sch['end_time']) ?></td>
									<td class="whitespace-nowrap px-3 py-4 text-sm">
										<?php $st = $sch['status'] ?? 'active';
											if ($st === 'active'): ?>
												<span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700 dark:bg-green-900/40 dark:text-green-400">Hoạt động</span>
											<?php elseif ($st === 'paused'): ?>
												<span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-400">Tạm dừng</span>
											<?php elseif ($st === 'cancelled'): ?>
												<span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700 dark:bg-red-900/40 dark:text-red-400">Đã hủy</span>
											<?php else: ?>
												<span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-1 text-xs font-medium text-slate-800"><?= htmlspecialchars($st) ?></span>
											<?php endif; ?>
									</td>
									<td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
										<a class="text-primary hover:text-primary/80 mr-3" href="edit.php?id=<?= $sch['schedule_id'] ?>&service_id=<?= $service_id ?>">Sửa</a>
										<form action="../../handle/schedules_process.php" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa lịch này?');">
											<input type="hidden" name="schedule_id" value="<?= $sch['schedule_id'] ?>">
											<input type="hidden" name="service_id" value="<?= $service_id ?>">
											<button type="submit" name="delete" class="text-red-600 hover:text-red-800">Xóa</button>
										</form>
									</td>
								</tr>
							<?php endforeach; else: ?>
								<tr><td colspan="5" class="text-center py-6">Chưa có lịch nào cho dịch vụ này.</td></tr>
							<?php endif; endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</main>
</div>
</body></html>

