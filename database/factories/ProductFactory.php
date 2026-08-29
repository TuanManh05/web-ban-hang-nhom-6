<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    private static int $sequence = 1;

    public function definition(): array
    {
        return [
            'name' => 'Sản phẩm mẫu ' . self::$sequence++,
            'price' => $this->faker->numberBetween(100, 990) * 1000,
            'category_id' => 1,
        ];
    }
}