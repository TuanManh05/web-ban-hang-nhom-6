<?php

declare(strict_types=1);

function database(): PDO
{
    static $connection = null;

    if ($connection instanceof PDO) {
        return $connection;
    }

    $localFile = __DIR__ . '/database.local.php';
    $local = is_file($localFile) ? require $localFile : [];

    $host = getenv('DB_HOST') ?: ($local['host'] ?? '127.0.0.1');
    $port = getenv('DB_PORT') ?: ($local['port'] ?? '3306');
    $name = getenv('DB_NAME') ?: ($local['name'] ?? 'web_ban_hang_nhom_6');
    $user = getenv('DB_USER') ?: ($local['user'] ?? 'root');
    $password = getenv('DB_PASSWORD') ?: ($local['password'] ?? '');
    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

    $connection = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $connection;
}
