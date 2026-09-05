<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

final class CartController
{
    public function index(): void
    {
        require __DIR__ . '/../views/cart.php';
    }

    public function addToCart(): void
    {
        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);
        $redirectUrl = $_SERVER['HTTP_REFERER'] ?? 'index.php';

        if ($quantity <= 0) {
            $_SESSION['cart_error'] = "Số lượng không hợp lệ!";
            header("Location: " . $redirectUrl);
            exit;
        }

        $conn = database();
        $stmt = $conn->prepare("SELECT id, name, price, stock FROM products WHERE id = :id");
        $stmt->execute(['id' => $productId]);
        $product = $stmt->fetch();

        if ($product) {
            $stock = (int)$product['stock'];
            if ($stock <= 0) {
                $_SESSION['cart_error'] = "Sản phẩm đã hết hàng!";
                header("Location: " . $redirectUrl);
                exit;
            }

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$productId])) {
                $newQty = $_SESSION['cart'][$productId]['quantity'] + $quantity;
                if ($newQty > $stock) {
                    $_SESSION['cart'][$productId]['quantity'] = $stock;
                    $_SESSION['cart_error'] = "Chỉ còn {$stock} sản phẩm. Đã điều chỉnh!";
                } else {
                    $_SESSION['cart'][$productId]['quantity'] = $newQty;
                    $_SESSION['cart_success'] = "Đã thêm vào giỏ hàng!";
                }
                $_SESSION['cart'][$productId]['stock'] = $stock;
            } else {
                if ($quantity > $stock) {
                    $_SESSION['cart'][$productId] = [
                        'name' => $product['name'], 'price' => $product['price'],
                        'quantity' => $stock, 'stock' => $stock
                    ];
                    $_SESSION['cart_error'] = "Đã thêm tối đa {$stock} sản phẩm.";
                } else {
                    $_SESSION['cart'][$productId] = [
                        'name' => $product['name'], 'price' => $product['price'],
                        'quantity' => $quantity, 'stock' => $stock
                    ];
                    $_SESSION['cart_success'] = "Đã thêm vào giỏ hàng!";
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
            $conn = database();
            $stmt = $conn->prepare("SELECT stock FROM products WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $product = $stmt->fetch();

            if (!$product) {
                unset($_SESSION['cart'][$id]);
                $_SESSION['cart_error'] = "Sản phẩm không còn tồn tại.";
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
                    $_SESSION['cart_error'] = "Sản phẩm đã hết hàng.";
                } elseif ($newQty > $stock) {
                    $_SESSION['cart'][$id]['quantity'] = $stock;
                    $_SESSION['cart_error'] = "Chỉ còn tối đa {$stock} sản phẩm!";
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
            $_SESSION['cart_success'] = "Đã xóa sản phẩm.";
        }
        header('Location: index.php?page=cart');
        exit;
    }
}