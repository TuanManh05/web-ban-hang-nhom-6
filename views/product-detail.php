<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
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
                <p class="text-secondary"><?= htmlspecialchars((string) ($product['category_name'] ?? 'Chưa phân loại'), ENT_QUOTES, 'UTF-8') ?></p>
                <p class="fs-4 fw-bold text-danger">
                    <?= number_format((float) $product['price'], 0, ',', '.') ?> đ
                </p>
                <p><?= nl2br(htmlspecialchars((string) ($product['description'] ?? 'Chưa có mô tả.'), ENT_QUOTES, 'UTF-8')) ?></p>
                <p class="fw-semibold">Tồn kho: <?= (int) $product['stock'] ?></p>
                <?php if ((int) $product['status'] === 1 && (int) $product['stock'] > 0): ?>
                    <form method="post" action="<?= $basePath ?>/views/cart.php" class="d-flex gap-2 mb-3">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                        <input type="hidden" name="redirect" value="product-detail.php?id=<?= (int) $product['id'] ?>">
                        <input class="form-control" style="max-width:100px" type="number" name="quantity" value="1" min="1" max="<?= (int) $product['stock'] ?>" aria-label="Số lượng">
                        <button class="btn btn-primary" type="submit">Thêm vào giỏ</button>
                    </form>
                <?php else: ?>
                    <p class="text-danger fw-semibold">Sản phẩm hiện đã hết hàng.</p>
                <?php endif; ?>
                <a href="<?= $basePath ?>/views/products.php" class="btn btn-outline-secondary">&laquo; Quay lại danh sách</a>
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
