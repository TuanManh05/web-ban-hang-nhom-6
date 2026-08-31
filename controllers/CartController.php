<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'models/Product.php'; 

class CartController {
    
    // THÊM SẢN PHẨM
    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? 0;
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

            // KIỂM TRA: Số lượng thêm phải > 0
            if ($quantity <= 0) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }

            $productModel = new Product();
            $product = $productModel->getProductById($productId);

            if ($product) {
                if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

                $stock = $product['stock'];
                
                if (isset($_SESSION['cart'][$productId])) {
                    $newQty = $_SESSION['cart'][$productId]['quantity'] + $quantity;
                    $_SESSION['cart'][$productId]['quantity'] = ($newQty > $stock) ? $stock : $newQty;
                } else {
                    if ($quantity <= $stock) {
                        $_SESSION['cart'][$productId] = [
                            'name' => $product['name'],
                            'price' => $product['price'],
                            'quantity' => $quantity,
                            'stock' => $stock
                        ];
                    }
                }
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    // CẬP NHẬT SỐ LƯỢNG (+/-)
    public function updateCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            
            // KIỂM TRA: ID phải tồn tại trong Session
            if (isset($_SESSION['cart'][$id])) {
                $action = $_POST['update_action'] ?? ''; 
                $currentQty = $_SESSION['cart'][$id]['quantity'];
                $stock = $_SESSION['cart'][$id]['stock'];

                $newQty = $currentQty;
                if ($action === 'increase') {
                    $newQty++;
                } elseif ($action === 'decrease') {
                    $newQty--;
                }

                if ($newQty <= 0) {
                    unset($_SESSION['cart'][$id]); // Xóa nếu giảm về 0
                } elseif ($newQty > $stock) {
                    $_SESSION['cart'][$id]['quantity'] = $stock; // Chặn quá tồn kho
                } else {
                    $_SESSION['cart'][$id]['quantity'] = $newQty;
                }
            }
            header('Location: index.php?page=cart');
            exit;
        }
    }

    // XÓA SẢN PHẨM
    public function removeFromCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            if (isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
            }
            header('Location: index.php?page=cart');
            exit;
        }
    }
}
?>
?>