<?php require __DIR__ . '/partials/header.php'; ?>
<div class="container mt-4 mb-5">
    <h2 class="mb-4 border-bottom pb-2">Giỏ hàng của bạn</h2>
    <?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
        <div class="alert alert-warning text-center shadow-sm py-5" role="alert">
            <h5 class="mb-3 text-secondary">Giỏ hàng của bạn đang trống!</h5>
            <a href="index.php" class="btn btn-primary px-4 mt-2">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="table-responsive shadow-sm bg-white rounded">
            <table class="table table-hover table-bordered align-middle text-center mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th style="width: 150px;">Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalPrice = 0; 
                    foreach ($_SESSION['cart'] as $id => $item): 
                        $subTotal = $item['price'] * $item['quantity'];
                        $totalPrice += $subTotal;
                    ?>
                        <tr>
                            <td class="text-start fw-medium"><?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= number_format((float)$item['price'], 0, ',', '.') ?> đ</td>
                            <td>
                                <form action="index.php?action=update_cart" method="POST" class="d-flex justify-content-center align-items-center mb-0">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" name="update_action" value="decrease" class="btn btn-outline-secondary btn-sm px-2">-</button>
                                    <input type="number" name="quantity" class="form-control form-control-sm text-center mx-1 border-secondary" value="<?= (int)$item['quantity'] ?>" readonly>
                                    <button type="submit" name="update_action" value="increase" class="btn btn-outline-secondary btn-sm px-2">+</button>
                                </form>
                            </td>
                            <td class="fw-bold text-danger"><?= number_format((float)$subTotal, 0, ',', '.') ?> đ</td>
                            <td>
                                <form action="index.php?action=remove_cart" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ?')" class="mb-0">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 p-3 bg-light rounded shadow-sm">
            <a href="index.php" class="btn btn-outline-secondary mb-3 mb-md-0"><i class="bi bi-arrow-left"></i> Tiếp tục mua sắm</a>
            <div class="text-end">
                <h4 class="mb-2">Tổng tiền: <span class="text-danger fw-bold fs-3"><?= number_format((float)$totalPrice, 0, ',', '.') ?> đ</span></h4>
                <button class="btn btn-success px-5 py-2 fw-bold">Tiến hành thanh toán</button>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>