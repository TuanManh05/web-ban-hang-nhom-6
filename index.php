<?php
declare(strict_types=1);
session_start();

require __DIR__ . '/config/database.php';
require __DIR__ . '/controllers/HomeController.php';
require __DIR__ . '/controllers/CartController.php';

$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? null;

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
        require __DIR__ . '/views/partials/header.php';
        echo "<div class='container mt-5'><h3>404 Not Found</h3></div>";
        require __DIR__ . '/views/partials/footer.php';
        break;
}