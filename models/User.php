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

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, name, email, password, role, status FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function updateName(int $id, string $name): bool
    {
        $stmt = $this->pdo->prepare('UPDATE users SET name = :name WHERE id = :id');
        return $stmt->execute(['name' => trim($name), 'id' => $id]);
    }

    public function updatePassword(int $id, string $password): bool
    {
        $stmt = $this->pdo->prepare('UPDATE users SET password = :password WHERE id = :id');
        return $stmt->execute(['password' => password_hash($password, PASSWORD_DEFAULT), 'id' => $id]);
    }
}
