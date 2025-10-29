<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản - QLDV</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- CSS riêng (nếu có) -->
    <link href="../css/login.css" rel="stylesheet">
    <link href="../css/footer.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(120deg, #89f7fe, #66a6ff);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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

        .card-custom {
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }
    </style>
</head>

<body>
    <section class="vh-100 d-flex justify-content-center align-items-center">
        <div class="container-fluid h-custom">
            <div class="d-flex flex-row align-items-center justify-content-center mb-4">
                <h2 class="brand-title">HỆ THỐNG QUẢN LÝ DỊCH VỤ CÔNG VIÊN (QLDV)</h2>
            </div>

            <div class="row d-flex justify-content-center align-items-center h-100">
                <!-- Cột hình ảnh giống login -->
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="../docs/logo/anh_dang_ky.jpg" 
                         style="width: 100%; height: auto; border-radius: 15px;"
                         class="img-fluid" alt="Ảnh minh họa đăng ký">
                </div>

                <!-- Cột form đăng ký giống layout login -->
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <div class="card card-custom p-3">
                        <div class="card-body">
                            <form action="../handle/register_handle.php" method="POST" class="needs-validation" novalidate>

                                <div class="mb-3">
                                    <label class="form-label" for="full_name">Tên đăng nhập</label>
                                    <input type="text" name="full_name" id="full_name" class="form-control form-control-lg" placeholder="Nhập họ và tên..." required>
                                    <div class="invalid-feedback">Vui lòng nhập họ và tên</div>
                                </div>


                                <div class="mb-3 position-relative">
                                    <label class="form-label" for="password">Mật khẩu</label>
                                    <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Nhập mật khẩu..." minlength="6" required>
                                    <i class="bi bi-eye-slash password-toggle" onclick="togglePassword('password')" style="position:absolute; right:16px; top:44px; cursor:pointer;"></i>
                                    <div class="invalid-feedback">Mật khẩu phải có ít nhất 6 ký tự</div>
                                </div>

                                <div class="mb-3 position-relative">
                                    <label class="form-label" for="confirm_password">Xác nhận mật khẩu</label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-lg" placeholder="Nhập lại mật khẩu..." required>
                                    <i class="bi bi-eye-slash password-toggle" onclick="togglePassword('confirm_password')" style="position:absolute; right:16px; top:44px; cursor:pointer;"></i>
                                    <div class="invalid-feedback">Mật khẩu xác nhận không khớp</div>
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

                                <div class="text-center mt-3">
                                    <button type="submit" name="register" class="btn btn-primary btn-lg w-100">Đăng ký</button>
                                </div>

                                <p class="text-center mt-3">Đã có tài khoản? <a href="../index.php" class="text-decoration-none">Đăng nhập</a></p>
                            </form>
                        </div>
                    </div>
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

    <!-- Custom JS: validation + password toggle -->
    <script>
        // Form validation (Bootstrap)
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        // custom password match check
                        var pwd = document.getElementById('password');
                        var cpwd = document.getElementById('confirm_password');
                        if (pwd && cpwd && pwd.value !== cpwd.value) {
                            cpwd.setCustomValidity('Mật khẩu xác nhận không khớp');
                        } else if (cpwd) {
                            cpwd.setCustomValidity('');
                        }

                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()

        // Toggle password visibility
        function togglePassword(id) {
            var input = document.getElementById(id);
            if (!input) return;
            var icon = input.parentElement.querySelector('.password-toggle');
            if (input.type === 'password') {
                input.type = 'text';
                if (icon) icon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                input.type = 'password';
                if (icon) icon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        }
    </script>
</body>
</html>
