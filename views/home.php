<?php require __DIR__ . '/partials/header.php'; ?>
<section class="hero py-5">
    <div class="container py-5 text-center">
        <span class="badge text-bg-primary mb-3">PHP + MySQL + XAMPP</span>
        <h1 class="display-5 fw-bold">Website bán hàng cơ bản</h1>
        <p class="lead text-secondary">Sản phẩm chất lượng - giá tốt mỗi ngày.</p>
    </div>
</section>

<section class="container pb-5">
    <h2 class="h4 fw-bold mb-4">Sản phẩm nổi bật</h2>
    <div class="row g-4">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card product-card h-100">
                        <a href="<?= $basePath ?>/views/product-detail.php?id=<?= urlencode((string) $product['id']) ?>"
                           class="text-decoration-none text-dark">
                            <img
                                src="<?= $product['image_path']
                                    ? htmlspecialchars($basePath . '/uploads/' . $product['image_path'], ENT_QUOTES, 'UTF-8')
                                    : $basePath . '/assets/img/product-placeholder.png' ?>"
                                class="card-img-top"
                                alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>">
                            <div class="card-body pb-2">
                                <h6 class="card-title text-truncate mb-1">
                                    <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>
                                </h6>
                                <p class="fw-bold text-danger mb-0">
                                    <?= number_format((float) $product['price'], 0, ',', '.') ?> đ
                                </p>
                            </div>
                        </a>
                        <div class="card-body pt-0">
                            <?php $stock = (int) ($product['stock'] ?? 0); ?>
                            <?php if ($stock > 0): ?>
                                <form method="post" action="<?= $basePath ?>/views/cart.php">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="redirect" value="<?= $basePath ?>/index.php">
                                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                                        Thêm vào giỏ
                                    </button>
                                </form>
                            <?php else: ?>
                                <button type="button" class="btn btn-sm btn-outline-secondary w-100" disabled>
                                    Hết hàng
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Chưa có sản phẩm nào.</p>
        <?php endif; ?>
    </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>