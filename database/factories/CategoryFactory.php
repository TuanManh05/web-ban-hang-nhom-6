<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['Điện thoại', 'Laptop', 'Phụ kiện', 'Thời trang', 'Giày dép', 'Đồ gia dụng', 'Đồng hồ', 'Thiết bị số'];
        $name = fake()->unique()->randomElement($categories);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}