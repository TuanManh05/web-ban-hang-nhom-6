<!-- Thay thế đoạn <td> chứa số lượng và <td> chứa nút Xóa thành code sau: -->

<td>
    <form action="index.php?action=update_cart" method="POST" class="d-flex justify-content-center align-items-center">
        <input type="hidden" name="id" value="<?= $id ?>">
        
        <!-- Nút Giảm (-) -->
        <button type="submit" name="update_action" value="decrease" class="btn btn-outline-secondary btn-sm">-</button>
        
        <!-- Ô nhập số (readonly để bắt buộc dùng nút bấm, hoặc bỏ readonly nếu cho gõ) -->
        <input type="number" name="quantity" class="form-control text-center mx-1" 
               value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>" 
               style="width: 60px;" readonly>
        
        <!-- Nút Tăng (+) -->
        <button type="submit" name="update_action" value="increase" class="btn btn-outline-secondary btn-sm">+</button>
    </form>
</td>

<td class="fw-bold text-danger"><?= number_format($subTotal, 0, ',', '.') ?> VNĐ</td>

<td>
    <!-- Dùng form POST để XÓA bảo mật hơn -->
    <form action="index.php?action=remove_cart" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
        <input type="hidden" name="id" value="<?= $id ?>">
        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
    </form>
</td>