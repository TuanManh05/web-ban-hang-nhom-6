<?php

declare(strict_types=1);

final class User
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, name, email, password, role, status
             FROM users
             WHERE email = :email
             LIMIT 1'
        );
        $stmt->execute([':email' => strtolower(trim($email))]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $stmt->execute([':email' => strtolower(trim($email))]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(string $name, string $email, string $password): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (name, email, password, role, status)
             VALUES (:name, :email, :password, 'customer', 1)"
        );
        $stmt->execute([
            ':name' => trim($name),
            ':email' => strtolower(trim($email)),
            ':password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}
