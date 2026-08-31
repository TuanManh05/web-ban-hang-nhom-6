<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Đảm bảo đã nạp model Product (bạn chỉnh lại đường dẫn cho đúng với project)
require_once 'models/Product.php'; 

class CartController {
    
    // THÊM VÀO GIỎ - Chỉ nhận ID và Số lượng
    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

            // Gọi database để lấy thông tin THẬT của sản phẩm
            $productModel = new Product();
            $product = $productModel->getProductById($productId); // Bạn thay bằng hàm thực tế của nhóm

            if ($product) {
                $name = $product['name'];
                $price = $product['price'];
                $stock = $product['stock'];

                if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

                if (isset($_SESSION['cart'][$productId])) {
                    $newQty = $_SESSION['cart'][$productId]['quantity'] + $quantity;
                    $_SESSION['cart'][$productId]['quantity'] = ($newQty > $stock) ? $stock : $newQty;
                } else {
                    if ($quantity <= $stock) {
                        $_SESSION['cart'][$productId] = [
                            'name' => $name,
                            'price' => $price,
                            'quantity' => $quantity,
                            'stock' => $stock
                        ];
                    }
                }
            }
            // Quay lại trang trước đó
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    // CẬP NHẬT SỐ LƯỢNG
    public function updateCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $action = $_POST['update_action']; // Nhận diện bấm nút + hay - hay gõ số
            $currentQty = $_SESSION['cart'][$id]['quantity'];
            $stock = $_SESSION['cart'][$id]['stock'];

            $newQty = $currentQty;
            if ($action === 'increase') {
                $newQty++;
            } elseif ($action === 'decrease') {
                $newQty--;
            } else {
                $newQty = (int)$_POST['quantity'];
            }

            // Xử lý giới hạn
            if ($newQty <= 0) {
                $this->removeFromCartLogic($id); // Số lượng = 0 thì tự xóa
            } elseif ($newQty > $stock) {
                $_SESSION['cart'][$id]['quantity'] = $stock;
            } else {
                $_SESSION['cart'][$id]['quantity'] = $newQty;
            }
            header('Location: index.php?page=cart');
            exit;
        }
    }

    // XÓA SẢN PHẨM (Phải dùng POST)
    public function removeFromCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $this->removeFromCartLogic($id);
            header('Location: index.php?page=cart');
            exit;
        }
    }

    private function removeFromCartLogic($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
    }
}
?>