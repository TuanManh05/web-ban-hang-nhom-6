<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'models/Product.php'; 

class CartController {
    
    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? 0;
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            // Xử lý chuyển trang an toàn
            $redirectUrl = $_SERVER['HTTP_REFERER'] ?? 'index.php';

            if ($quantity <= 0) {
                $_SESSION['cart_error'] = "Số lượng không hợp lệ!";
                header("Location: $redirectUrl");
                exit;
            }

            $productModel = new Product();
            $product = $productModel->getProductById($productId);

            if ($product) {
                if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

                $stock = $product['stock'];
                
                if (isset($_SESSION['cart'][$productId])) {
                    $newQty = $_SESSION['cart'][$productId]['quantity'] + $quantity;
                    
                    if ($newQty > $stock) {
                        $_SESSION['cart'][$productId]['quantity'] = $stock;
                        $_SESSION['cart_error'] = "Sản phẩm chỉ còn $stock cái trong kho. Đã tự động điều chỉnh số lượng!";
                    } else {
                        $_SESSION['cart'][$productId]['quantity'] = $newQty;
                        $_SESSION['cart_success'] = "Đã thêm sản phẩm vào giỏ hàng!";
                    }
                } else {
                    if ($quantity > $stock) {
                        $_SESSION['cart'][$productId] = [
                            'name' => $product['name'], 'price' => $product['price'],
                            'quantity' => $stock, 'stock' => $stock
                        ];
                        $_SESSION['cart_error'] = "Vượt quá tồn kho! Đã thêm tối đa $stock sản phẩm.";
                    } else {
                        $_SESSION['cart'][$productId] = [
                            'name' => $product['name'], 'price' => $product['price'],
                            'quantity' => $quantity, 'stock' => $stock
                        ];
                        $_SESSION['cart_success'] = "Đã thêm sản phẩm vào giỏ hàng!";
                    }
                }
            } else {
                $_SESSION['cart_error'] = "Sản phẩm không tồn tại!";
            }
            
            header("Location: $redirectUrl");
            exit;
        }
    }

    public function updateCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            
            if (isset($_SESSION['cart'][$id])) {
                $action = $_POST['update_action'] ?? ''; 
                $currentQty = $_SESSION['cart'][$id]['quantity'];
                $stock = $_SESSION['cart'][$id]['stock'];

                $newQty = $currentQty;
                if ($action === 'increase') { $newQty++; } 
                elseif ($action === 'decrease') { $newQty--; }

                if ($newQty <= 0) {
                    unset($_SESSION['cart'][$id]);
                    $_SESSION['cart_success'] = "Đã xóa sản phẩm khỏi giỏ.";
                } elseif ($newQty > $stock) {
                    $_SESSION['cart'][$id]['quantity'] = $stock;
                    $_SESSION['cart_error'] = "Đã đạt giới hạn tối đa ($stock sản phẩm) trong kho!";
                } else {
                    $_SESSION['cart'][$id]['quantity'] = $newQty;
                }
            }
            header('Location: index.php?page=cart');
            exit;
        }
    }

    public function removeFromCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            if (isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
                $_SESSION['cart_success'] = "Đã xóa sản phẩm thành công.";
            }
            header('Location: index.php?page=cart');
            exit;
        }
    }
}