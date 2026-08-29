<?php
// Chú ý: Cần có session_start() ở index.php của dự án rồi nhé!
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class CartController {
    
    // 1. Thêm sản phẩm vào giỏ
    public function addToCart($id, $name, $price, $quantity, $stock) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Nếu sản phẩm đã có -> Tăng số lượng
        if (isset($_SESSION['cart'][$id])) {
            $newQuantity = $_SESSION['cart'][$id]['quantity'] + $quantity;
            
            // Kiểm tra không vượt tồn kho
            if ($newQuantity > $stock) {
                $_SESSION['cart'][$id]['quantity'] = $stock; // Đưa về mức tối đa
                return "Chỉ có thể mua tối đa $stock sản phẩm này!";
            } else {
                $_SESSION['cart'][$id]['quantity'] = $newQuantity;
            }
        } else {
            // Thêm mới
            if ($quantity > $stock) {
                return "Vượt quá số lượng tồn kho!";
            }
            $_SESSION['cart'][$id] = [
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'stock' => $stock
            ];
        }
        return "Thêm thành công!";
    }

    // 2. Cập nhật số lượng khi bấm Tăng/Giảm
    public function updateCart($id, $quantity) {
        if (isset($_SESSION['cart'][$id])) {
            $stock = $_SESSION['cart'][$id]['stock'];
            if ($quantity > 0 && $quantity <= $stock) {
                $_SESSION['cart'][$id]['quantity'] = $quantity;
            }
        }
    }

    // 3. Xóa sản phẩm khỏi giỏ
    public function removeFromCart($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
    }
}
?>