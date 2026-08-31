<form action="index.php?action=add_cart" method="POST">
    <!-- CHỈ gửi ID sản phẩm, không gửi Giá/Tên -->
    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
    <input type="hidden" name="quantity" value="1"> <!-- Mặc định thêm 1 -->
    <button type="submit" class="btn btn-primary w-100">Thêm vào giỏ</button>
</form>