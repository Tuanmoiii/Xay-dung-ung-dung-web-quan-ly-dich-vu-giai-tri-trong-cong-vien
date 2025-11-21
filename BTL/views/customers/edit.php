<?php
session_start();
require_once __DIR__ . '/../../functions/auth_functions.php';
require_once __DIR__ . '/../../functions/customers_functions.php';
checkLogin();

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    $_SESSION['error'] = 'ID khách hàng không hợp lệ';
    header('Location: list.php');
    exit();
}

$customer = getCustomerById($id);
if (!$customer) {
    $_SESSION['error'] = 'Không tìm thấy khách hàng';
    header('Location: list.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sửa khách hàng</title>
    <link href="../../css/login.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Sửa khách hàng</h3>
            <a href="list.php" class="btn btn-secondary">Quay lại</a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form action="../../handle/customers_process.php" method="POST">
            <input type="hidden" name="customer_id" value="<?php echo $customer['customer_id']; ?>">

            <div class="mb-3">
                <label class="form-label">Họ và tên</label>
                <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($customer['full_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($customer['email']); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Điện thoại</label>
                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($customer['phone']); ?>">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" name="update" class="btn btn-primary">Lưu</button>
                <a href="list.php" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</body>
</html>
