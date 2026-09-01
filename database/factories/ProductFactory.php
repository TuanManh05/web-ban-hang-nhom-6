<?php

class ProductFactory {
    private static $brands = ['Sony', 'Canon', 'Dell', 'Asus', 'Apple', 'Samsung', 'Logitech', 'Xiaomi'];
    private static $types = ['Pro', 'Ultra', 'Max', 'Lite', 'Plus', 'Gaming', 'Wireless'];

    public static function definition(int $categoryId): array {
        $name = self::$brands[array_rand(self::$brands)] . ' ' . self::$types[array_rand(self::$types)] . ' ' . rand(100, 999);
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name))) . '-' . rand(1000, 9999);

        return [
            'category_id' => $categoryId,
            'name'        => $name,
            'slug'        => $slug,
            'price'       => rand(500, 5000) * 10000,
            'description' => 'Mô tả tự động cho sản phẩm ' . $name,
        ];
    }

    public static function generate(int $count, array $categoryIds = [1, 2, 3, 4]): array {
        $products = [];
        for ($i = 0; $i < $count; $i++) {
            $randomCategoryId = $categoryIds[array_rand($categoryIds)];
            $products[] = self::definition($randomCategoryId);
        }
        return $products;
    }
}