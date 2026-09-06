<?php declare(strict_types=1); ?>
<?php require __DIR__ . '/partials/header.php'; ?>
<section class="container py-5">
    <div class="card border-0 shadow-sm mx-auto text-center" style="max-width: 640px;">
        <div class="card-body p-4 p-md-5">
            <div class="display-5 mb-3" aria-hidden="true">✓</div>
            <h1 class="h3 fw-bold">Đặt hàng thành công</h1>
            <p class="text-secondary mb-2">Mã đơn hàng của bạn là:</p>
            <p class="fs-3 fw-bold text-primary mb-4">
                <?= htmlspecialchars($orderCode, ENT_QUOTES, 'UTF-8') ?>
            </p>
            <a href="<?= $basePath ?>/index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
        </div>
    </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
