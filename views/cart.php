<div class="container mt-5">
    <h2 class="mb-4">Giỏ hàng của bạn</h2>

    <?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
        <!-- TRẠNG THÁI TRỐNG -->
        <div class="alert alert-warning text-center shadow-sm" role="alert">
            <h5 class="mb-3">Giỏ hàng của bạn đang trống!</h5>
            <a href="index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <!-- BẢNG GIỎ HÀNG -->
        <div class="table-responsive shadow-sm">
            <table class="table table-bordered align-middle text-center mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
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
                            <td class="text-start fw-medium"><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= number_format($item['price'], 0, ',', '.') ?> đ</td>
                            
                            <!-- CỘT SỐ LƯỢNG (+ / -) -->
                            <td>
                                <form action="index.php?action=update_cart" method="POST" class="d-flex justify-content-center align-items-center mb-0">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <button type="submit" name="update_action" value="decrease" class="btn btn-outline-secondary btn-sm">-</button>
                                    <input type="number" name="quantity" class="form-control text-center mx-1" 
                                           value="<?= $item['quantity'] ?>" style="width: 60px;" readonly>
                                    <button type="submit" name="update_action" value="increase" class="btn btn-outline-secondary btn-sm">+</button>
                                </form>
                            </td>
                            
                            <td class="fw-bold text-danger"><?= number_format($subTotal, 0, ',', '.') ?> đ</td>
                            
                            <!-- CỘT XÓA -->
                            <td>
                                <form action="index.php?action=remove_cart" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')" class="mb-0">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- TỔNG TIỀN -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
            <a href="index.php" class="btn btn-outline-secondary mb-3 mb-md-0">← Tiếp tục mua sắm</a>
            <div class="text-end">
                <h4>Tổng tiền dự kiến: <span class="text-danger fw-bold"><?= number_format($totalPrice, 0, ',', '.') ?> đ</span></h4>
                <button class="btn btn-success mt-2 px-5">Tiến hành thanh toán</button>
            </div>
        </div>
    <?php endif; ?>
</div>