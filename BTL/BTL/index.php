<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QLDV - Hệ thống quản lý dịch vụ công viên</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- CSS riêng -->
    <link href="./css/login.css" rel="stylesheet">
    <link href="./css/footer.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(120deg, #89f7fe, #66a6ff);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .login-container {
            flex: 1;
        }

        .brand-title {
            font-weight: bold;
            color: #0066cc;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-primary {
            background-color: #0066cc;
            border: none;
        }

        .btn-primary:hover {
            background-color: #004999;
        }

        .footer {
            background: #003366;
            color: #fff;
            text-align: center;
            padding: 15px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <section class="vh-100 d-flex justify-content-center align-items-center login-container">
        <div class="container-fluid h-custom">
            <div class="d-flex flex-row align-items-center justify-content-center mb-4">
                <h2 class="brand-title">HỆ THỐNG QUẢN LÝ DỊCH VỤ CÔNG VIÊN (QLDV)</h2>
            </div>

            <div class="row d-flex justify-content-center align-items-center h-100">
                <!-- Cột hình ảnh -->
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="./docs/logo/anh_dang_nhap.jpg" 
                         style="width: 100%; height: auto; border-radius: 15px;"
                         class="img-fluid" alt="Ảnh minh họa công viên">
                </div>

                <!-- Cột form đăng nhập -->
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <form action="./handle/login_process.php" method="POST">

                        <!-- Họ tên -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="full_name">Tên đăng nhập (Họ tên)</label>
                            <input type="text" name="full_name" id="full_name"
                                   class="form-control form-control-lg"
                                   placeholder="Nhập họ tên nhân viên..." required />
                        </div>

                        <!-- Mật khẩu -->
                        <div class="form-outline mb-3">
                            <label class="form-label" for="password">Mật khẩu</label>
                            <input type="password" name="password" id="password"
                                   class="form-control form-control-lg"
                                   placeholder="Nhập mật khẩu..." required />
                        </div>

                        <!-- Thông báo session -->
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger text-center" role="alert">
                                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success text-center" role="alert">
                                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Nút đăng nhập -->
                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" name="login" class="btn btn-primary btn-lg w-100 mb-3">
                                Đăng nhập
                            </button>
                            
                            <!-- Liên kết đăng ký -->
                            <div class="text-center">
                                <p class="mb-0">Chưa có tài khoản? 
                                    <a href="./views/register.php" class="text-primary text-decoration-none fw-bold">
                                        Đăng ký ngay
                                        <i class="bi bi-arrow-right-circle ms-1"></i>
                                    </a>
                                </p>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        Hệ thống QLDV | FITDNU - Open Source
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
