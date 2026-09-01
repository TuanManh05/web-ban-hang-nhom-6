<?php
require_once 'models/Product.php';
$productModel = new Product();
$products = $productModel->getAllProducts();
?>

<div class="container mt-4 mb-5">
    <h2 class="mb-4 border-bottom pb-2">Danh sách sản phẩm</h2>
    <div class="row g-4">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <h5 class="card-title text-truncate" title="<?= htmlspecialchars($product['name']) ?>">
                                <?= htmlspecialchars($product['name']) ?>
                            </h5>
                            <p class="text-danger fw-bold fs-5 mb-2">
                                <?= number_format($product['price'], 0, ',', '.') ?> đ
                            </p>
                            <p class="text-muted small mb-3">Tồn kho: <?= $product['stock'] ?></p>
                            
                            <!-- Form Thêm vào giỏ -->
                            <form action="index.php?action=add_cart" method="POST">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary w-100" <?= ($product['stock'] <= 0) ? 'disabled' : '' ?>>
                                    <?= ($product['stock'] <= 0) ? 'Hết hàng' : 'Thêm vào giỏ' ?>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center text-muted">
                <p>Chưa có sản phẩm nào trong cửa hàng.</p>
            </div>
        <?php endif; ?>
    </div>
</div>