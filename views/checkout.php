<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Order.php';

$pageTitle = 'Thanh toán';

$items = Cart::getItems();

// Không cho thanh toán khi giỏ hàng trống
if (empty($items)) {
    header('Location: cart.php?error=' . urlencode('Giỏ hàng đang trống, không thể thanh toán.'));
    exit;
}

$errors = [];
$old = [
    'name'    => '',
    'phone'   => '',
    'address' => '',
    'note'    => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['name']    = trim((string) ($_POST['name'] ?? ''));
    $old['phone']   = trim((string) ($_POST['phone'] ?? ''));
    $old['address'] = trim((string) ($_POST['address'] ?? ''));
    $old['note']    = trim((string) ($_POST['note'] ?? ''));

    if ($old['name'] === '') {
        $errors['name'] = 'Vui lòng nhập họ tên.';
    } elseif (mb_strlen($old['name']) > 100) {
        $errors['name'] = 'Họ tên không được vượt quá 100 ký tự.';
    }

    if ($old['phone'] === '') {
        $errors['phone'] = 'Vui lòng nhập số điện thoại.';
    } elseif (!preg_match('/^(0|\+84)[0-9]{9,10}$/', $old['phone'])) {
        $errors['phone'] = 'Số điện thoại không hợp lệ (vd: 0912345678).';
    }

    if ($old['address'] === '') {
        $errors['address'] = 'Vui lòng nhập địa chỉ nhận hàng.';
    } elseif (mb_strlen($old['address']) > 255) {
        $errors['address'] = 'Địa chỉ không được vượt quá 255 ký tự.';
    }

    if (mb_strlen($old['note']) > 500) {
        $errors['note'] = 'Ghi chú không được vượt quá 500 ký tự.';
    }

    // Kiểm tra lại giỏ hàng ngay trước khi đặt (đề phòng tồn kho vừa thay đổi)
    $items = Cart::getItems();
    if (empty($items)) {
        header('Location: cart.php?error=' . urlencode('Giỏ hàng đang trống, không thể thanh toán.'));
        exit;
    }

    if (empty($errors)) {
        $total = Cart::getTotalAmount();
        try {
            $orderId = Order::create($old, $items, $total);
            Cart::clear();
            header('Location: checkout-success.php?order=' . $orderId);
            exit;
        } catch (\Throwable $e) {
            $errors['general'] = $e->getMessage() !== ''
                ? $e->getMessage()
                : 'Đặt hàng thất bại, vui lòng thử lại.';
        }
    }
}

$total = Cart::getTotalAmount();

require __DIR__ . '/partials/header.php';
?>
<section class="container py-5">
    <h1 class="h3 fw-bold mb-4">Thanh toán</h1>

    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errors['general'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($errors) && empty($errors['general'])): ?>
        <div class="alert alert-danger">Vui lòng kiểm tra lại các trường được đánh dấu bên dưới.</div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-7 order-2 order-lg-1">
            <form method="post" action="checkout.php" novalidate>
                <div class="mb-3">
                    <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name"
                           class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($old['name'], ENT_QUOTES, 'UTF-8') ?>">
                    <?php if (isset($errors['name'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['name'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                    <input type="text" id="phone" name="phone"
                           class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($old['phone'], ENT_QUOTES, 'UTF-8') ?>">
                    <?php if (isset($errors['phone'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['phone'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Địa chỉ nhận hàng <span class="text-danger">*</span></label>
                    <input type="text" id="address" name="address"
                           class="form-control <?= isset($errors['address']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($old['address'], ENT_QUOTES, 'UTF-8') ?>">
                    <?php if (isset($errors['address'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['address'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label for="note" class="form-label">Ghi chú (không bắt buộc)</label>
                    <textarea id="note" name="note" rows="3"
                              class="form-control <?= isset($errors['note']) ? 'is-invalid' : '' ?>"><?= htmlspecialchars($old['note'], ENT_QUOTES, 'UTF-8') ?></textarea>
                    <?php if (isset($errors['note'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['note'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">Đặt hàng</button>
            </form>
        </div>

        <div class="col-lg-5 order-1 order-lg-2">
            <div class="card">
                <div class="card-body">
                    <h2 class="h5 fw-bold mb-3">Đơn hàng của bạn</h2>
                    <ul class="list-group list-group-flush mb-3">
                        <?php foreach ($items as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start px-0">
                                <div>
                                    <p class="mb-0 fw-medium"><?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <small class="text-secondary">
                                        <?= $item['quantity'] ?> x <?= number_format($item['price'], 0, ',', '.') ?> đ
                                    </small>
                                </div>
                                <span class="fw-semibold text-nowrap">
                                    <?= number_format($item['subtotal'], 0, ',', '.') ?> đ
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="d-flex justify-content-between border-top pt-3">
                        <span class="fw-bold">Tổng tiền</span>
                        <span class="fw-bold text-danger fs-5"><?= number_format($total, 0, ',', '.') ?> đ</span>
                    </div>
                </div>
            </div>
            <a href="cart.php" class="btn btn-outline-secondary w-100 mt-3">&laquo; Quay lại giỏ hàng</a>
        </div>
    </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>