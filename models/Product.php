<?php
<<<<<<< HEAD
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
=======

declare(strict_types=1);

final class Product
{
    /**
     * Lấy danh sách sản phẩm còn bán, kèm ảnh đại diện (is_primary = 1) nếu có.
     */
    public static function featured(int $limit = 8): array
    {
        $pdo = database();

        $sql = 'SELECT p.id, p.name, p.slug, p.price, pi.image_path
                FROM products p
                LEFT JOIN product_images pi
                    ON pi.product_id = p.id AND pi.is_primary = 1
                WHERE p.status = 1
                ORDER BY p.created_at DESC
                LIMIT :limit';

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $pdo = database();

        $stmt = $pdo->prepare(
            'SELECT p.*, pi.image_path
             FROM products p
             LEFT JOIN product_images pi
                 ON pi.product_id = p.id AND pi.is_primary = 1
             WHERE p.id = :id'
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $product = $stmt->fetch();

        return $product ?: null;
    }
}
>>>>>>> ad1105a123b1ff4fdf74b866336060b9d899c96f
