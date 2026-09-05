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

require_once __DIR__ . '/../../models/Cart.php';
$cartCount = Cart::getTotalQuantity();
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Nhóm 6', ENT_QUOTES, 'UTF-8') ?> | Nhóm 6 Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $basePath ?>/assets/css/app.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
    <div class="container">
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
                    <a class="nav-link d-flex align-items-center gap-1" href="<?= $basePath ?>/views/cart.php">
                        Giỏ hàng
                        <?php if ($cartCount > 0): ?>
                            <span class="badge rounded-pill bg-danger"><?= $cartCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<main>