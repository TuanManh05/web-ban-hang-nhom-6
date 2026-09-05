<?php require __DIR__ . '/partials/header.php'; ?>
<section class="hero py-5">
    <div class="container py-5 text-center">
        <span class="badge text-bg-primary mb-3">PHP + MySQL + XAMPP</span>
        <h1 class="display-5 fw-bold">Website bán hàng cơ bản</h1>
        <p class="lead text-secondary">Sản phẩm chất lượng - giá tốt mỗi ngày.</p>
<<<<<<< HEAD
=======
    </div>
</section>

<section class="container pb-5">
    <h2 class="h4 fw-bold mb-4">Sản phẩm nổi bật</h2>
    <div class="row g-4">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="<?= $basePath ?>/views/product-detail.php?id=<?= urlencode((string) $product['id']) ?>"
                       class="text-decoration-none text-dark">
                        <div class="card product-card h-100">
                            <img
                                src="<?= $product['image_path']
                                    ? htmlspecialchars($basePath . '/uploads/' . $product['image_path'], ENT_QUOTES, 'UTF-8')
                                    : $basePath . '/assets/img/product-placeholder.png' ?>"
                                class="card-img-top"
                                alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>">
                            <div class="card-body">
                                <h6 class="card-title text-truncate mb-1">
                                    <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>
                                </h6>
                                <p class="fw-bold text-danger mb-0">
                                    <?= number_format((float) $product['price'], 0, ',', '.') ?> đ
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Chưa có sản phẩm nào.</p>
        <?php endif; ?>
>>>>>>> ad1105a123b1ff4fdf74b866336060b9d899c96f
    </div>
</section>

<section class="container pb-5">
    <h2 class="h4 fw-bold mb-4">Sản phẩm nổi bật</h2>
    <div class="row g-4">
        <?php if (!empty($products) && is_array($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card product-card h-100 shadow-sm border-0">
                        <a href="<?= $basePath ?? '' ?>/views/product-detail.php?id=<?= urlencode((string)$product['id']) ?>" class="text-decoration-none text-dark">
                            <img src="<?= !empty($product['image_path']) ? htmlspecialchars(($basePath ?? '') . '/uploads/' . $product['image_path'], ENT_QUOTES, 'UTF-8') : ($basePath ?? '') . '/assets/img/product-placeholder.png' ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>">
                        </a>
                        <div class="card-body d-flex flex-column">
                            <a href="<?= $basePath ?? '' ?>/views/product-detail.php?id=<?= urlencode((string)$product['id']) ?>" class="text-decoration-none text-dark">
                                <h6 class="card-title text-truncate mb-1" title="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>
                                </h6>
                            </a>
                            <p class="fw-bold text-danger mb-3">
                                <?= number_format((float) $product['price'], 0, ',', '.') ?> đ
                            </p>
                            <div class="mt-auto">
                                <form action="<?= $basePath ?? '' ?>/index.php?action=add_cart" method="POST">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars((string)$product['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <?php $stock = isset($product['stock']) ? (int)$product['stock'] : 1; ?>
                                    <button type="submit" class="btn btn-outline-primary w-100 btn-sm fw-medium" <?= $stock <= 0 ? 'disabled' : '' ?>>
                                        <i class="bi bi-cart-plus"></i> <?= $stock <= 0 ? 'Hết hàng' : 'Thêm vào giỏ' ?>
                                    </button>
                                </form>
                            </div>
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
        <?php if (!empty($products) && is_array($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card product-card h-100 shadow-sm border-0">
                        <a href="<?= $basePath ?? '' ?>/views/product-detail.php?id=<?= urlencode((string)$product['id']) ?>" class="text-decoration-none text-dark">
                            <img src="<?= !empty($product['image_path']) ? htmlspecialchars(($basePath ?? '') . '/uploads/' . $product['image_path'], ENT_QUOTES, 'UTF-8') : ($basePath ?? '') . '/assets/img/product-placeholder.png' ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>">
                        </a>
                        <div class="card-body d-flex flex-column">
                            <a href="<?= $basePath ?? '' ?>/views/product-detail.php?id=<?= urlencode((string)$product['id']) ?>" class="text-decoration-none text-dark">
                                <h6 class="card-title text-truncate mb-1" title="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>
                                </h6>
                            </a>
                            <p class="fw-bold text-danger mb-3">
                                <?= number_format((float) $product['price'], 0, ',', '.') ?> đ
                            </p>
                            <div class="mt-auto">
                                <form action="<?= $basePath ?? '' ?>/index.php?action=add_cart" method="POST">
                                    <input type="hidden" name="product_id" value="<?= htmlspecialchars((string)$product['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <?php $stock = isset($product['stock']) ? (int)$product['stock'] : 1; ?>
                                    <button type="submit" class="btn btn-outline-primary w-100 btn-sm fw-medium" <?= $stock <= 0 ? 'disabled' : '' ?>>
                                        <i class="bi bi-cart-plus"></i> <?= $stock <= 0 ? 'Hết hàng' : 'Thêm vào giỏ' ?>
                                    </button>
                                </form>
                            </div>
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