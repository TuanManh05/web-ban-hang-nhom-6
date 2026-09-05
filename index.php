<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/CartController.php';

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
        echo "<div style='text-align:center; margin-top:50px;'><h3>404 Not Found</h3></div>";
        break;
}
=======

// Bật hiển thị lỗi chi tiết để dễ dàng kiểm tra
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

// Nạp các file cấu hình và Controller cần thiết
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/ProductController.php';
require_once __DIR__ . '/controllers/CategoryController.php';

// Lấy kết nối PDO từ hàm database()
$pdo = database();

// Lấy tham số action từ URL (mặc định quay về 'home')
$action = $_GET['action'] ?? 'home';

switch ($action) {
    // ==========================================
    // 0. Trang Dashboard Quản trị trung tâm (Admin Index)
    // ==========================================
    case 'admin':
        require_once __DIR__ . '/views/admin/index.php';
        break;

    // ==========================================
    // 1. Luồng Quản lý Sản phẩm (Product CRUD)
    // ==========================================
    case 'product-index':
    case 'products':
        $controller = new ProductController($pdo);
        $controller->index();
        break;

    case 'product-create':
        $controller = new ProductController($pdo);
        $controller->create();
        break;

    case 'product-store':
        $controller = new ProductController($pdo);
        $controller->store();
        break;

    case 'product-edit':
        $controller = new ProductController($pdo);
        $controller->edit();
        break;

    case 'product-update':
        $controller = new ProductController($pdo);
        $controller->update();
        break;

    case 'product-delete':
        $controller = new ProductController($pdo);
        $controller->delete();
        break;

    // ==========================================
    // 2. Luồng Quản lý Danh mục (Category CRUD)
    // ==========================================
    case 'category-index':
    case 'categories':
        $controller = new CategoryController($pdo);
        $controller->index();
        break;

    case 'category-create':
        $controller = new CategoryController($pdo);
        $controller->create();
        break;

    case 'category-store':
        $controller = new CategoryController($pdo);
        $controller->store();
        break;

    case 'category-edit':
        $controller = new CategoryController($pdo);
        $controller->edit();
        break;

    case 'category-update':
        $controller = new CategoryController($pdo);
        $controller->update();
        break;

    case 'category-delete':
        $controller = new CategoryController($pdo);
        $controller->delete();
        break;

    // ==========================================
    // 3. Trang chủ ứng dụng (Default)
    // ==========================================
    case 'home':
    default:
        $controller = new HomeController();
        $controller->index();
        break;
>>>>>>> ad1105a123b1ff4fdf74b866336060b9d899c96f
}