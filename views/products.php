<?php

declare(strict_types=1);

$pageTitle = 'Sản phẩm';

require __DIR__ . '/partials/header.php';
?>
<section class="container py-5 text-center">
    <h1 class="h3 fw-bold mb-3">Trang sản phẩm</h1>
    <p class="text-secondary">
        Trang danh sách đầy đủ sản phẩm (lọc, tìm kiếm, phân trang) đang được nhóm hoàn thiện.
        Bạn có thể xem sản phẩm nổi bật ngay tại <a href="<?= $basePath ?>/index.php">trang chủ</a>.
    </p>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
