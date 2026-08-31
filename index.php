<?php
session_start();
require_once 'controllers/HomeController.php';
require_once 'controllers/CartController.php'; // Nạp Controller

$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? null;

// XỬ LÝ ACTIONS (POST)
if ($action) {
    $cartController = new CartController();
    switch ($action) {
        case 'add_cart':
            $cartController->addToCart();
            break;
        case 'update_cart':
            $cartController->updateCart();
            break;
        case 'remove_cart':
            $cartController->removeFromCart();
            break;
    }
}

// XỬ LÝ PAGE (Hiển thị giao diện)
switch ($page) {
    case 'home':
        $homeController = new HomeController();
        $homeController->index();
        break;
    case 'cart':
        // Gọi file giao diện giỏ hàng
        require_once 'views/partials/header.php';
        require_once 'views/cart.php';
        require_once 'views/partials/footer.php';
        break;
    default:
        echo "404 Not Found";
        break;
}
?>