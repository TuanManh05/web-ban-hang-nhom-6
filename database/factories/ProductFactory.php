<?php

declare(strict_types=1);

final class ProductFactory
{
    private const BRANDS = ['Sony', 'Canon', 'Dell', 'Asus', 'Apple', 'Samsung', 'Logitech', 'Xiaomi'];
    private const TYPES = ['Pro', 'Ultra', 'Max', 'Lite', 'Plus', 'Gaming', 'Wireless'];

    public static function definition(int $categoryId, int $sequence): array
    {
        $name = self::BRANDS[array_rand(self::BRANDS)]
            . ' ' . self::TYPES[array_rand(self::TYPES)]
            . ' ' . random_int(100, 999);

        $baseSlug = strtolower(trim((string) preg_replace('/[^A-Za-z0-9]+/', '-', $name), '-'));

        return [
            'category_id' => $categoryId,
            'name' => $name,
            'slug' => $baseSlug . '-' . $sequence . '-' . bin2hex(random_bytes(3)),
            'price' => random_int(50, 5000) * 10000,
            'stock' => random_int(5, 50),
            'status' => 1,
            'description' => 'Sản phẩm mẫu phục vụ phát triển: ' . $name,
        ];
    }

    public static function generate(int $count, array $categoryIds): array
    {
        if ($count < 1 || $categoryIds === []) {
            return [];
        }

        $products = [];
        for ($i = 1; $i <= $count; $i++) {
            $categoryId = (int) $categoryIds[array_rand($categoryIds)];
            $products[] = self::definition($categoryId, $i);
        }

        return $products;
    }
}
