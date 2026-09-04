<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Product.php';

final class HomeController
{
    public function index(): void
    {
        $pageTitle = 'Trang chủ';
        $products = Product::featured();

        require __DIR__ . '/../views/home.php';
    }
}
