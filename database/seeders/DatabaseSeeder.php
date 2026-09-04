<?php

require_once __DIR__ . '/../factories/ProductFactory.php';

class DatabaseSeeder {
    private $pdo;

    public function __construct() {
        // Ưu tiên đọc cấu hình từ file config chung của dự án nếu có
        $configFile = __DIR__ . '/../../config/database.php'; // Điều chỉnh đường dẫn file config dự án nếu cần
        
        $host    = defined('DB_HOST') ? DB_HOST : '127.0.0.1';
        $port    = defined('DB_PORT') ? DB_PORT : '3306';
        $db      = defined('DB_NAME') ? DB_NAME : 'web-ban-hang-nhom-6';
        $user    = defined('DB_USER') ? DB_USER : 'root';
        $pass    = defined('DB_PASS') ? DB_PASS : '';
        $charset = 'utf8mb4';

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            // Step 1: Kết nối MySQL root để tạo CSDL nếu chưa tồn tại
            $pdoRoot = new PDO("mysql:host=$host;port=$port;charset=$charset", $user, $pass, $options);
            $pdoRoot->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

            // Step 2: Kết nối chính thức vào CSDL
            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            die("Lỗi kết nối CSDL: " . $e->getMessage());
        }
    }

    public function run() {
        // Tạo bảng với đầy đủ cột stock và status cho Products
        $this->pdo->exec("
            SET FOREIGN_KEY_CHECKS = 0;
            DROP TABLE IF EXISTS products;
            DROP TABLE IF EXISTS categories;
            DROP TABLE IF EXISTS users;
            SET FOREIGN_KEY_CHECKS = 1;

            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                slug VARCHAR(255) UNIQUE NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            CREATE TABLE products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                category_id INT NOT NULL,
                name VARCHAR(255) NOT NULL,
                slug VARCHAR(255) UNIQUE NOT NULL,
                price DECIMAL(12, 2) NOT NULL CHECK (price >= 0),
                stock INT NOT NULL DEFAULT 10,
                status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_products_categories
                    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Mã hóa Bcrypt chuẩn xác 100% khớp với Mật khẩu thực tế
        $adminPass = password_hash('11111111', PASSWORD_BCRYPT);
        $customerPass = password_hash('88888888', PASSWORD_BCRYPT);

        $stmtUser = $this->pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmtUser->execute(['System Admin', 'admin@gmail.com', $adminPass, 'admin']);
        $stmtUser->execute(['Customer', 'customer@gmail.com', $customerPass, 'customer']);

        $categories = [
            1 => ['Điện thoại', 'dien-thoai'],
            2 => ['Laptop', 'laptop'],
            3 => ['Phụ kiện', 'phu-kien'],
            4 => ['Máy ảnh', 'may-anh']
        ];
        $stmtCat = $this->pdo->prepare("INSERT INTO categories (id, name, slug) VALUES (?, ?, ?)");
        foreach ($categories as $id => $cat) {
            $stmtCat->execute([$id, $cat[0], $cat[1]]);
        }

        // Sinh dữ liệu mẫu bao gồm stock & status
        $randomProducts = ProductFactory::generate(15, [1, 2, 3, 4]);
        $stmtProd = $this->pdo->prepare("INSERT INTO products (category_id, name, slug, price, stock, status, description) VALUES (?, ?, ?, ?, ?, ?, ?)");

        foreach ($randomProducts as $prod) {
            $stmtProd->execute([
                $prod['category_id'],
                $prod['name'],
                $prod['slug'],
                $prod['price'],
                $prod['stock'] ?? rand(5, 50),
                $prod['status'] ?? 'active',
                $prod['description']
            ]);
        }

        echo "Tải lại CSDL web-ban-hang-nhom-6 thành công! Đã cập nhật đủ stock & status.\n";
    }
}

$seeder = new DatabaseSeeder();
$seeder->run();