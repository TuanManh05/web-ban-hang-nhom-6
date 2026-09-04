<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/CartController.php'; // Nạp controller mới

$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? null;

// 1. XỬ LÝ LOGIC GIỎ HÀNG
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action) {
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

// 2. NẠP HEADER (Chứa Bootstrap, CSS và Thanh menu Giỏ hàng)
require_once __DIR__ . '/views/partials/header.php';

// 3. ĐIỀU HƯỚNG HIỂN THỊ GIAO DIỆN (Router)
switch ($page) {
    case 'home':
        $controller = new HomeController();
        $controller->index();
        break;
    case 'cart':
        $controller = new CartController();
        $controller->index();
        break;
    default:
        echo "<div class='container mt-5'><h3>404 - Không tìm thấy trang</h3></div>";
        break;
}

// 4. NẠP FOOTER
if (file_exists(__DIR__ . '/views/partials/footer.php')) {
    require_once __DIR__ . '/views/partials/footer.php';
}