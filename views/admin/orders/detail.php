<?php declare(strict_types=1); require __DIR__ . '/../../partials/header.php'; ?>
<section class="container py-5">
<?php if (!$order): ?><div class="alert alert-danger">Không tìm thấy đơn hàng.</div><?php else: ?>
<h1 class="h3 fw-bold">Đơn hàng DH<?= str_pad((string) $order['id'], 6, '0', STR_PAD_LEFT) ?></h1>
<?php if (!empty($_GET['msg'])): ?><div class="alert alert-success"><?= htmlspecialchars((string) $_GET['msg'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<?php if (!empty($_GET['error'])): ?><div class="alert alert-danger"><?= htmlspecialchars((string) $_GET['error'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<p><strong>Khách hàng:</strong> <?= htmlspecialchars((string) $order['customer_name'], ENT_QUOTES, 'UTF-8') ?> — <?= htmlspecialchars((string) $order['phone'], ENT_QUOTES, 'UTF-8') ?></p>
<p><strong>Địa chỉ:</strong> <?= htmlspecialchars((string) $order['address'], ENT_QUOTES, 'UTF-8') ?></p>
<form method="post" action="<?= $basePath ?>/index.php?action=admin-order-status" class="row g-2 mb-4">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"><input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">
<div class="col-auto"><select class="form-select" name="status"><?php foreach (['pending'=>'Chờ xác nhận','confirmed'=>'Đã xác nhận','shipping'=>'Đang giao','completed'=>'Hoàn thành','cancelled'=>'Đã hủy'] as $value=>$label): ?><option value="<?= $value ?>" <?= $order['status'] === $value ? 'selected' : '' ?>><?= $label ?></option><?php endforeach; ?></select></div><div class="col-auto"><button class="btn btn-primary">Cập nhật</button></div></form>
<div class="table-responsive"><table class="table"><thead><tr><th>Sản phẩm</th><th>Giá</th><th>SL</th><th>Thành tiền</th></tr></thead><tbody><?php foreach ($order['items'] as $item): ?><tr><td><?= htmlspecialchars((string) $item['product_name'], ENT_QUOTES, 'UTF-8') ?></td><td><?= number_format((float) $item['price'], 0, ',', '.') ?> đ</td><td><?= (int) $item['quantity'] ?></td><td><?= number_format((float) $item['price'] * (int) $item['quantity'], 0, ',', '.') ?> đ</td></tr><?php endforeach; ?></tbody></table></div>
<p class="text-end fs-5 fw-bold">Tổng cộng: <?= number_format((float) $order['total_amount'], 0, ',', '.') ?> đ</p><?php endif; ?>
<a class="btn btn-outline-secondary" href="<?= $basePath ?>/index.php?action=admin-orders">Quay lại danh sách</a></section><?php require __DIR__ . '/../../partials/footer.php'; ?>
