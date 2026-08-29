<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Nhóm 6', ENT_QUOTES, 'UTF-8') ?> | Nhóm 6 Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Nhóm 6 Shop</a>
    </div>
</nav>
<main>
<?php
// Tính tổng số lượng sản phẩm trong giỏ hàng
$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}
?>

<!-- Nút giỏ hàng trên giao diện -->
<div class="d-flex align-items-center">
    <a href="index.php?page=cart" class="btn btn-outline-dark">
        <i class="bi bi-cart"></i> Giỏ hàng 
        <span class="badge bg-danger rounded-pill" id="cart-count"><?= $cartCount ?></span>
    </a>
</div>