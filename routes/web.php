<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\AdminProductController;

// 1. Tự động chuyển hướng về trang đăng nhập khi truy cập trang chủ
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// 2. Trang Đăng nhập & Đăng xuất chung
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. Quyền Khách hàng (Customer) - Bắt buộc đã đăng nhập
Route::middleware(['auth'])->group(function () {
    Route::get('/shop', [ProductController::class, 'index'])->name('shop');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/add-to-cart/{id}', [CartController::class, 'add'])->name('cart.add');
});

// 4. Quyền Quản trị viên (Admin) - Bắt buộc đã đăng nhập và là Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.products.')->group(function () {
    Route::get('/', function () {
        return view('admin');
    })->name('dashboard');

    Route::get('/products', [AdminProductController::class, 'index'])->name('index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('edit');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('destroy');
});