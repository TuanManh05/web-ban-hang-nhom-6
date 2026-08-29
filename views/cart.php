<div class="container mt-5">
    <h2 class="mb-4">Giỏ hàng của bạn</h2>

    <?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
        <!-- Trạng thái giỏ hàng trống -->
        <div class="alert alert-warning text-center" role="alert">
            Giỏ hàng của bạn đang trống! <br>
            <a href="index.php" class="btn btn-primary mt-3">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <!-- Bảng danh sách sản phẩm (Responsive) -->
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
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
                            <td class="text-start"><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= number_format($item['price'], 0, ',', '.') ?> VNĐ</td>
                            
                            <!-- Cột Số lượng có nút Tăng/Giảm -->
                            <td>
                                <form action="index.php?action=update_cart" method="POST" class="d-flex justify-content-center align-items-center">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <input type="number" name="quantity" class="form-control text-center mx-1" 
                                           value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>" 
                                           style="width: 80px;" onchange="this.form.submit()">
                                    <!-- max=stock giúp không cho nhập vượt quá tồn kho -->
                                </form>
                            </td>
                            
                            <td class="fw-bold text-danger"><?= number_format($subTotal, 0, ',', '.') ?> VNĐ</td>
                            
                            <!-- Nút Xóa -->
                            <td>
                                <a href="index.php?action=remove_cart&id=<?= $id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Phần Tổng tiền -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="index.php" class="btn btn-outline-secondary">← Tiếp tục mua sắm</a>
            <div class="text-end">
                <h4>Tổng tiền dự kiến: <span class="text-danger fw-bold"><?= number_format($totalPrice, 0, ',', '.') ?> VNĐ</span></h4>
                <a href="index.php?page=checkout" class="btn btn-success mt-2 px-5">Thanh toán</a>
            </div>
        </div>
    <?php endif; ?>
</div>