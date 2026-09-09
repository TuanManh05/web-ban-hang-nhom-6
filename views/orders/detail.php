<?php declare(strict_types=1); require __DIR__ . '/../partials/header.php'; ?>
<section class="container py-5">
<?php if (!$order): ?><div class="alert alert-danger">Đơn hàng không tồn tại hoặc không thuộc tài khoản của bạn.</div>
<?php else: ?>
    <h1 class="h3 fw-bold">Đơn hàng DH<?= str_pad((string) $order['id'], 6, '0', STR_PAD_LEFT) ?></h1>
    <p>Trạng thái: <strong><?= htmlspecialchars((string) $order['status'], ENT_QUOTES, 'UTF-8') ?></strong></p>
    <p>Người nhận: <?= htmlspecialchars((string) $order['customer_name'], ENT_QUOTES, 'UTF-8') ?> — <?= htmlspecialchars((string) $order['phone'], ENT_QUOTES, 'UTF-8') ?></p>
    <p>Địa chỉ: <?= htmlspecialchars((string) $order['address'], ENT_QUOTES, 'UTF-8') ?></p>
    <div class="table-responsive"><table class="table"><thead><tr><th>Sản phẩm</th><th>Đơn giá</th><th>Số lượng</th><th>Thành tiền</th></tr></thead><tbody>
    <?php foreach ($order['items'] as $item): ?><tr><td><?= htmlspecialchars((string) $item['product_name'], ENT_QUOTES, 'UTF-8') ?></td><td><?= number_format((float) $item['price'], 0, ',', '.') ?> đ</td><td><?= (int) $item['quantity'] ?></td><td><?= number_format((float) $item['price'] * (int) $item['quantity'], 0, ',', '.') ?> đ</td></tr><?php endforeach; ?>
    </tbody></table></div>
    <p class="text-end fs-5 fw-bold">Tổng cộng: <?= number_format((float) $order['total_amount'], 0, ',', '.') ?> đ</p>
<?php endif; ?>
<a href="<?= $basePath ?>/index.php?action=orders" class="btn btn-outline-secondary">Quay lại</a>
</section><?php require __DIR__ . '/../partials/footer.php'; ?>
