<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Product.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$product = $id > 0 ? Product::findById($id) : null;

$pageTitle = $product ? $product['name'] : 'Sản phẩm không tồn tại';

require __DIR__ . '/partials/header.php';
?>
<section class="container py-5">
    <?php if ($product): ?>
        <div class="row g-4">
            <div class="col-md-5">
                <img
                    src="<?= $product['image_path']
                        ? htmlspecialchars($basePath . '/uploads/' . $product['image_path'], ENT_QUOTES, 'UTF-8')
                        : $basePath . '/assets/img/product-placeholder.png' ?>"
                    class="img-fluid rounded border"
                    alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="col-md-7">
                <h1 class="h3 fw-bold"><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="fs-4 fw-bold text-danger">
                    <?= number_format((float) $product['price'], 0, ',', '.') ?> đ
                </p>
                <a href="<?= $basePath ?>/index.php" class="btn btn-outline-secondary">&laquo; Quay lại trang chủ</a>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <h1 class="h4 fw-bold mb-3">Sản phẩm không tồn tại</h1>
            <p class="text-secondary">Sản phẩm bạn tìm có thể đã bị xoá hoặc đường dẫn không đúng.</p>
            <a href="<?= $basePath ?>/index.php" class="btn btn-primary">Về trang chủ</a>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
