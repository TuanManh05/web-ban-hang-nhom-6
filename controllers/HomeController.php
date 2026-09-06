<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

final class HomeController
{
    public function index(): void
    {
        $conn = database();
        $stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
        $products = $stmt->fetchAll();
        $basePath = ''; 

        require __DIR__ . '/../views/home.php';
    }
}