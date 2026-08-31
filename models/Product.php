<?php
class Product {
    // Hàm mô phỏng lấy tất cả sản phẩm
    public function getAllProducts() {
        return [
            1 => ['id' => 1, 'name' => 'Áo thun nam', 'price' => 200000, 'stock' => 10],
            2 => ['id' => 2, 'name' => 'Quần jean nữ', 'price' => 350000, 'stock' => 5],
            3 => ['id' => 3, 'name' => 'Giày thể thao', 'price' => 500000, 'stock' => 2]
        ];
    }

    // Hàm lấy 1 sản phẩm theo ID để thêm vào giỏ hàng
    public function getProductById($id) {
        $products = $this->getAllProducts();
        return $products[$id] ?? null; // Trả về null nếu ID không tồn tại
    }
}
?>