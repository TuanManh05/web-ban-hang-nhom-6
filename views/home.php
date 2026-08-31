<?php
require_once 'models/Product.php';
$productModel = new Product();
$products = $productModel->getAllProducts();
?>

<div class="container mt-5">
    <h2 class="mb-4">Danh sách sản phẩm</h2>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="card-text text-danger fw-bold mb-1"><?= number_format($product['price'], 0, ',', '.') ?> VNĐ</p>
                        <p class="card-text text-muted small">Tồn kho: <?= $product['stock'] ?></p>
                        
                        <!-- Form thêm vào giỏ -->
                        <form action="index.php?action=add_cart" method="POST">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary w-100">Thêm vào giỏ</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>