<?php

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
