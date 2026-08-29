<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tạo Tài khoản Admin và Customer
        User::create([
            'name' => 'Quản Trị Viên',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Khách Hàng',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'customer',
        ]);

        // 2. Tạo Danh mục mẫu
        $category = Category::create([
            'name' => 'Danh mục mặc định',
            'slug' => Str::slug('Danh mục mặc định')
        ]);

        // 3. DÙNG FACTORY để sinh 15 sản phẩm mẫu
        Product::factory(15)->create([
            'category_id' => $category->id,
        ]);
    }
}