<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Đặt hàng thành công';
$orderId = isset($_GET['order']) ? (int) $_GET['order'] : 0;

require __DIR__ . '/partials/header.php';
?>
<section class="container py-5 text-center">
    <p class="display-1 mb-3">✅</p>
    <h1 class="h3 fw-bold mb-3">Đặt hàng thành công!</h1>
    <?php if ($orderId > 0): ?>
        <p class="text-secondary">Mã đơn hàng của bạn là <strong>#<?= $orderId ?></strong>.</p>
    <?php endif; ?>
    <p class="text-secondary mb-4">Chúng tôi sẽ liên hệ để xác nhận đơn hàng trong thời gian sớm nhất.</p>
    <a href="<?= $basePath ?>/index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>