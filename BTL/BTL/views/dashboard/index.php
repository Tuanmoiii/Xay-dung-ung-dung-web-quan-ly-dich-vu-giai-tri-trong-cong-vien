<?php
// Kiểm tra đăng nhập
require_once __DIR__ . '/../../functions/auth_functions.php';
checkLogin('../../index.php');

// Lấy thông tin người dùng hiện tại
$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng điều khiển - QLDV</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/main.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            background-color: #0d6efd;
            min-height: 100vh;
            color: white;
            padding-top: 20px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            border-radius: 8px;
            margin: 5px 15px;
        }

        .sidebar a:hover,
        .sidebar .active {
            background-color: #0056b3;
        }

        .content {
            padding: 2rem;
        }

        .card {
            border-radius: 12px;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        footer {
            background: #e9ecef;
            padding: 10px 0;
            text-align: center;
            margin-top: 2rem;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="text-center mb-4">
                <img src="../../images/fitdnu_logo.png" class="img-fluid mb-2" style="max-width: 80px;" alt="Logo">
                <h5>QLDV - FITDNU</h5>
            </div>

            <a href="index.php" class="active">🏠 Trang chủ</a>
            <a href="../services/list.php">🧾 Quản lý dịch vụ</a>
            <a href="../schedules/list.php">🗓️ Quản lý lịch chiếu</a>
            <a href="../customers/list.php">👤 Quản lý khách hàng</a>
            <a href="../bookings/history.php">🎟️ Quản lý đặt vé</a>
            <a href="../payments/list.php">💳 Quản lý thanh toán</a>

            <div class="mt-auto text-center">
                <a href="../../handle/logout_process.php" class="btn btn-light text-primary mt-3">Đăng xuất</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg bg-light shadow-sm">
                <div class="container-fluid">
                    <span class="navbar-text">
                        Xin chào, <strong><?= htmlspecialchars($currentUser['full_name']) ?></strong>
                        <?php if (!empty($currentUser['role'])): ?>
                            <span class="text-muted"> (<?= htmlspecialchars($currentUser['role']) ?>)</span>
                        <?php endif; ?>
                    </span>
                </div>
            </nav>

            <!-- Dashboard Content -->
            <div class="content container">
                <h3 class="mb-4">🎯 Bảng điều khiển hệ thống QLDV</h3>
                <p>Chọn một trong các chức năng bên dưới để bắt đầu quản lý dữ liệu.</p>

                <div class="row g-4 mt-3">
                    <div class="col-md-4">
                        <a href="../services/list.php" class="text-decoration-none text-dark">
                            <div class="card p-3 text-center">
                                <h5>🧾 Dịch vụ</h5>
                                <p>Quản lý các loại dịch vụ cung cấp.</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="../schedules/list.php" class="text-decoration-none text-dark">
                            <div class="card p-3 text-center">
                                <h5>🗓️ Lịch chiếu / Lịch trình</h5>
                                <p>Cập nhật và theo dõi lịch trình dịch vụ.</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="../customers/list.php" class="text-decoration-none text-dark">
                            <div class="card p-3 text-center">
                                <h5>👤 Khách hàng</h5>
                                <p>Quản lý thông tin khách hàng sử dụng dịch vụ.</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="../bookings/history.php" class="text-decoration-none text-dark">
                            <div class="card p-3 text-center">
                                <h5>🎟️ Đặt vé</h5>
                                <p>Xem, xác nhận và quản lý các đơn đặt vé.</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="../payments/list.php" class="text-decoration-none text-dark">
                            <div class="card p-3 text-center">
                                <h5>💳 Thanh toán</h5>
                                <p>Theo dõi, xử lý và cập nhật trạng thái thanh toán.</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer>
               FITDNU Open Source | Quản Lý Dịch Vụ
            </footer>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
