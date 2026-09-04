<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

final class Product
{
    public static function featured(): array
    {
        $conn = database();
        // Giả sử bảng tên là products
        $stmt = $conn->query("SELECT * FROM products LIMIT 10");
        return $stmt->fetchAll();
    }

    // Hàm bổ sung cho CartController gọi
    public static function findById(int $id): ?array
    {
        $conn = database();
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();
        return $product ?: null;
    }
}