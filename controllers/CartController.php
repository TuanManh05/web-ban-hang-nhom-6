<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/Product.php';

final class CartController
{
    // Hàm hiển thị giao diện giỏ hàng
    public function index(): void
    {
        $pageTitle = 'Giỏ hàng';
        require __DIR__ . '/../views/cart.php';
    }

    public function addToCart(): void
    {
        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);
        $redirectUrl = $_SERVER['HTTP_REFERER'] ?? 'index.php';

        if ($quantity <= 0) {
            $_SESSION['cart_error'] = "Số lượng thêm không hợp lệ!";
            header("Location: " . $redirectUrl);
            exit;
        }

        // Lấy thông tin thật từ DB bằng hàm tĩnh
        $product = Product::findById($productId);

        if ($product) {
            $stock = (int)$product['stock'];

            if ($stock <= 0) {
                $_SESSION['cart_error'] = "Sản phẩm này hiện tại đã hết hàng!";
                header("Location: " . $redirectUrl);
                exit;
            }

            if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
            
            if (isset($_SESSION['cart'][$productId])) {
                $newQty = $_SESSION['cart'][$productId]['quantity'] + $quantity;
                if ($newQty > $stock) {
                    $_SESSION['cart'][$productId]['quantity'] = $stock;
                    $_SESSION['cart_error'] = "Chỉ còn {$stock} sản phẩm trong kho. Đã tự điều chỉnh!";
                } else {
                    $_SESSION['cart'][$productId]['quantity'] = $newQty;
                    $_SESSION['cart_success'] = "Đã thêm sản phẩm vào giỏ hàng!";
                }
                $_SESSION['cart'][$productId]['stock'] = $stock; // Cập nhật lại tồn kho mới nhất
            } else {
                if ($quantity > $stock) {
                    $_SESSION['cart'][$productId] = [
                        'name' => $product['name'], 'price' => $product['price'],
                        'quantity' => $stock, 'stock' => $stock
                    ];
                    $_SESSION['cart_error'] = "Vượt tồn kho! Đã thêm tối đa {$stock} sản phẩm.";
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
        
        header("Location: " . $redirectUrl);
        exit;
    }

    public function updateCart(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        
        if (isset($_SESSION['cart'][$id])) {
            // Lấy lại DB để check tồn kho mới nhất
            $product = Product::findById($id);

            if (!$product) {
                unset($_SESSION['cart'][$id]);
                $_SESSION['cart_error'] = "Sản phẩm không còn tồn tại trên hệ thống.";
            } else {
                $stock = (int)$product['stock'];
                $_SESSION['cart'][$id]['stock'] = $stock; 

                $action = $_POST['update_action'] ?? ''; 
                $currentQty = $_SESSION['cart'][$id]['quantity'];
                $newQty = $currentQty;

                if ($action === 'increase') { $newQty++; } 
                elseif ($action === 'decrease') { $newQty--; }

                if ($newQty <= 0) {
                    unset($_SESSION['cart'][$id]);
                    $_SESSION['cart_success'] = "Đã xóa sản phẩm khỏi giỏ.";
                } elseif ($stock <= 0) {
                    unset($_SESSION['cart'][$id]);
                    $_SESSION['cart_error'] = "Sản phẩm này đã hết hàng và bị xóa khỏi giỏ.";
                } elseif ($newQty > $stock) {
                    $_SESSION['cart'][$id]['quantity'] = $stock;
                    $_SESSION['cart_error'] = "Chỉ còn tối đa {$stock} sản phẩm trong kho!";
                } else {
                    $_SESSION['cart'][$id]['quantity'] = $newQty;
                }
            }
        }
        header('Location: index.php?page=cart');
        exit;
    }

    public function removeFromCart(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
            $_SESSION['cart_success'] = "Đã xóa sản phẩm thành công.";
        }
        header('Location: index.php?page=cart');
        exit;
    }
}