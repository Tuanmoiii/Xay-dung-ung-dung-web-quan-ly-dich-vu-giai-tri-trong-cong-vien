<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
checkLogin();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Thêm khách hàng</title>
    <link href="../../css/login.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Thêm khách hàng</h3>
            <a href="list.php" class="btn btn-secondary">Quay lại</a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form action="../../handle/customers_process.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Họ và tên</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Điện thoại</label>
                <input type="text" name="phone" class="form-control">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" name="create" class="btn btn-primary">Tạo</button>
                <a href="list.php" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</body>
</html>
