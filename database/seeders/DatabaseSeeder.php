<?php

require_once __DIR__ . '/../factories/ProductFactory.php';

class DatabaseSeeder {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO('sqlite:' . __DIR__ . '/../database.sqlite');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function run() {
        // 1. Re-create Tables
        $this->pdo->exec("
            DROP TABLE IF EXISTS products;
            DROP TABLE IF EXISTS categories;
            DROP TABLE IF EXISTS users;

            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL,
                role TEXT CHECK(role IN ('admin', 'customer')) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                slug TEXT UNIQUE NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE products (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category_id INTEGER NOT NULL,
                name TEXT NOT NULL,
                slug TEXT UNIQUE NOT NULL,
                price REAL NOT NULL,
                description TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
            );
        ");

        // 2. Seed Users
        $adminPass = password_hash('11111111', PASSWORD_BCRYPT);
        $customerPass = password_hash('88888888', PASSWORD_BCRYPT);

        $stmtUser = $this->pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmtUser->execute(['System Admin', 'admin@gmail.com', $adminPass, 'admin']);
        $stmtUser->execute(['Customer', 'customer@gmail.com', $customerPass, 'customer']);

        // 3. Seed Categories
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

        // 4. Seed 15 Products via Factory
        $randomProducts = ProductFactory::generate(15, [1, 2, 3, 4]);
        $stmtProd = $this->pdo->prepare("INSERT INTO products (category_id, name, slug, price, description) VALUES (?, ?, ?, ?, ?)");

        foreach ($randomProducts as $prod) {
            $stmtProd->execute([
                $prod['category_id'],
                $prod['name'],
                $prod['slug'],
                $prod['price'],
                $prod['description']
            ]);
        }

        echo "Seeded database successfully via Seeder & Factory!\n";
    }
}

$seeder = new DatabaseSeeder();
$seeder->run();