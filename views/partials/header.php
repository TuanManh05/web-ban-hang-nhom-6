<<<<<<< HEAD
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Bán Hàng - Nhóm 6</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
=======
<?php declare(strict_types=1);

/**
 * Tự tính đường dẫn gốc của dự án (base path), để CSS/JS/link menu
 * luôn đúng dù trang đang mở nằm ở gốc (index.php) hay trong views/.
 * Ví dụ: nếu dự án chạy tại http://localhost/web-ban-hang-nhom-6/,
 * $basePath sẽ tự động là "/web-ban-hang-nhom-6".
 */
$documentRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
$projectRoot = realpath(__DIR__ . '/../..');
$basePath = '';
if ($documentRoot && $projectRoot && str_starts_with($projectRoot, $documentRoot)) {
    $basePath = str_replace('\\', '/', substr($projectRoot, strlen($documentRoot)));
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Nhóm 6', ENT_QUOTES, 'UTF-8') ?> | Nhóm 6 Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $basePath ?>/assets/css/app.css" rel="stylesheet">
>>>>>>> ad1105a123b1ff4fdf74b866336060b9d899c96f
</head>
<body>
<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
<<<<<<< HEAD
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
            <div class="d-flex align-items-center">
                <?php 
                $cartCount = 0;
                if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) { 
                        $cartCount += isset($item['quantity']) ? (int)$item['quantity'] : 0; 
                    }
                }
                ?>
                <a href="index.php?page=cart" class="btn btn-outline-dark">
                    <i class="bi bi-cart-fill"></i> Giỏ hàng 
                    <span class="badge bg-danger rounded-pill ms-1"><?= $cartCount ?></span>
                </a>
            </div>
=======
        <a class="navbar-brand fw-bold" href="<?= $basePath ?>/index.php">Nhóm 6 Shop</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar" aria-controls="mainNavbar"
                aria-expanded="false" aria-label="Mở menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $basePath ?>/index.php">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $basePath ?>/views/products.php">Sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $basePath ?>/views/cart.php">Giỏ hàng</a>
                </li>
            </ul>
>>>>>>> ad1105a123b1ff4fdf74b866336060b9d899c96f
        </div>
    </div>
</nav>

<div class="container mt-3">
    <?php if (isset($_SESSION['cart_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> 
            <?= htmlspecialchars($_SESSION['cart_success'], ENT_QUOTES, 'UTF-8') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['cart_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['cart_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> 
            <?= htmlspecialchars($_SESSION['cart_error'], ENT_QUOTES, 'UTF-8') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['cart_error']); ?>
    <?php endif; ?>
