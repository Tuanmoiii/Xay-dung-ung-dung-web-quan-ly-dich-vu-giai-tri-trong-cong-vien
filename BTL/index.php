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
            position: relative;
        }
        .bg-overlay {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: url('./images/bg-park.jpg') center/cover no-repeat;
            opacity: 0.18;
            z-index: 0;
        }
        .login-container {
            flex: 1;
            z-index: 1;
        }
        .brand-title {
            font-weight: bold;
            color: #0066cc;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.18);
            font-size: 2.1rem;
            letter-spacing: 1px;
        }
        .form-control {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .btn-primary {
            background: linear-gradient(90deg, #0066cc 60%, #66a6ff 100%);
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #004999 60%, #66a6ff 100%);
        }
        .img-fluid {
            box-shadow: 0 4px 24px rgba(0,0,0,0.13);
            border: 3px solid #fff;
            transition: transform 0.2s;
        }
        .img-fluid:hover {
            transform: scale(1.04);
        }
        .footer {
            background: linear-gradient(90deg, #003366 60%, #0066cc 100%);
            color: #fff;
            text-align: center;
            padding: 18px 0 10px 0;
            font-size: 15px;
            box-shadow: 0 -2px 12px rgba(0,0,0,0.08);
            position: relative;
        }
        .footer .footer-icons {
            margin-bottom: 8px;
        }
        .footer .footer-icons a {
            color: #fff;
            margin: 0 8px;
            font-size: 1.3rem;
            transition: color 0.2s;
        }
        .footer .footer-icons a:hover {
            color: #89f7fe;
        }
        .footer .footer-desc {
            font-size: 14px;
            opacity: 0.85;
        }
    </style>
</head>

<body>
    <div class="bg-overlay"></div>
    <section class="vh-100 d-flex justify-content-center align-items-center login-container position-relative">
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
        <div class="footer-icons mb-2">
            <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" title="Email"><i class="bi bi-envelope-fill"></i></a>
            <a href="#" title="Github"><i class="bi bi-github"></i></a>
        </div>
        <div class="footer-desc">
            <span><i class="bi bi-tree-fill"></i> Hệ thống QLDV | FITDNU - Open Source</span><br>
            <span>Liên hệ: <a href="mailto:support@fitdnu.edu.vn" class="text-info text-decoration-none">support@fitdnu.edu.vn</a></span>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
