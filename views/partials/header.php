<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Bán Hàng</title>
    <!-- Nhớ link Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Nhóm 6</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Trang chủ</a>
                </li>
            </ul>

            <!-- Nút Giỏ hàng -->
            <div class="d-flex align-items-center">
                <?php 
                $cartCount = 0;
                if (isset($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as$item) { $cartCount +=$item['quantity']; }
                }
                ?>
                <a href="index.php?page=cart" class="btn btn-outline-dark">
                    <i class="bi bi-cart-fill"></i> Giỏ hàng 
                    <span class="badge bg-danger rounded-pill ms-1"><?= $cartCount ?></span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- KHU VỰC THÔNG BÁO FLASH MESSAGE -->
<div class="container mt-3">
    <?php if (isset($_SESSION['cart_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($_SESSION['cart_success']) ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Bán Hàng</title>
    <!-- Bootstrap CSS -->
    <link href="[https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css](https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css)" rel="stylesheet">
    <link href="[https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css](https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css)" rel="stylesheet">
</head>
<body>

<?php 
// Đảm bảo session đã được khởi tạo để dùng được $_SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Nhóm 6</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Trang chủ</a>
                </li>
            </ul>

            <!-- Nút Giỏ hàng -->
            <div class="d-flex align-items-center">
                <?php 
                $cartCount = 0;
                // Thêm kiểm tra is_array để tránh lỗi nếu session cart bị sai định dạng
                if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) { 
                        $cartCount += isset($item['quantity']) ? $item['quantity'] : 0; 
                    }
                }
                ?>
                <a href="index.php?page=cart" class="btn btn-outline-dark">
                    <i class="bi bi-cart-fill"></i> Giỏ hàng 
                    <span class="badge bg-danger rounded-pill ms-1"><?= $cartCount ?></span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- KHU VỰC THÔNG BÁO FLASH MESSAGE -->
<div class="container mt-3">
    <?php if (isset($_SESSION['cart_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= $_SESSION['cart_success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['cart_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['cart_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $_SESSION['cart_error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['cart_error']); ?>
    <?php endif; ?>
</div>

<!-- Bootstrap JS (Rất quan trọng để nút X tắt thông báo và Menu Mobile hoạt động) -->
<script src="[https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js](https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js)"></script>

</body>
</html>