<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/config/database.php';
require __DIR__ . '/controllers/HomeController.php';

$controller = new HomeController();
$controller->index();
