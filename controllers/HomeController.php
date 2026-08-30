<?php

declare(strict_types=1);

final class HomeController
{
    public function index(): void
    {
        $pageTitle = 'Trang chủ';
        require __DIR__ . '/../views/home.php';
    }
}
