<?php declare(strict_types=1); require __DIR__ . '/../partials/header.php'; ?>
<section class="container py-5">
    <h1 class="h3 fw-bold mb-4">Lịch sử đơn hàng</h1>
    <?php if ($orders === []): ?>
        <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
    <?php else: ?>
        <div class="table-responsive"><table class="table align-middle">
            <thead><tr><th>Mã đơn</th><th>Ngày đặt</th><th>Tổng tiền</th><th>Trạng thái</th><th></th></tr></thead>
            <tbody><?php foreach ($orders as $item): ?><tr>
                <td>DH<?= str_pad((string) $item['id'], 6, '0', STR_PAD_LEFT) ?></td>
                <td><?= htmlspecialchars((string) $item['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= number_format((float) $item['total_amount'], 0, ',', '.') ?> đ</td>
                <td><?= htmlspecialchars((string) $item['status'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><a class="btn btn-sm btn-outline-primary" href="<?= $basePath ?>/index.php?action=order-detail&amp;id=<?= (int) $item['id'] ?>">Xem</a></td>
            </tr><?php endforeach; ?></tbody>
        </table></div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../partials/footer.php'; ?>
