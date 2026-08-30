<?php
// models/ProductModel.php

class ProductModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Lấy danh sách tất cả sản phẩm kèm tên Danh mục (JOIN categories)
     */
    public function getAllProducts() {
        $sql = "SELECT p.*, c.name AS category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                ORDER BY p.id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lấy thông tin chi tiết một sản phẩm theo ID
     */
    public function getProductById($id) {
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Thêm mới sản phẩm vào CSDL 
     */
    public function insertProduct($data) {
        $sql = "INSERT INTO products (category_id, name, slug, price, stock, status) 
                VALUES (:category_id, :name, :slug, :price, :stock, :status)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':category_id' => $data['category_id'],
            ':name'        => $data['name'],
            ':slug'        => $data['slug'],
            ':price'       => $data['price'],
            ':stock'       => $data['stock'],
            ':status'      => $data['status'] ?? 1
        ]);
    }

    /**
     * Cập nhật thông tin sản phẩm theo ID
     */
    public function updateProduct($id, $data) {
        $sql = "UPDATE products 
                SET category_id = :category_id, 
                    name        = :name, 
                    slug        = :slug, 
                    price       = :price, 
                    stock       = :stock, 
                    status      = :status 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':category_id' => $data['category_id'],
            ':name'        => $data['name'],
            ':slug'        => $data['slug'],
            ':price'       => $data['price'],
            ':stock'       => $data['stock'],
            ':status'      => $data['status'] ?? 1,
            ':id'          => $id
        ]);
    }

    /**
     * Xóa sản phẩm theo ID
     */
    public function deleteProduct($id) {
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Lấy danh sách tất cả Danh mục để hiển thị trong dropdown
     */
    public function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Kiểm tra Slug đã tồn tại trong CSDL hay chưa (tránh vi phạm UNIQUE)
     */
    public function isSlugExists($slug, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) FROM products WHERE slug = ? AND id != ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$slug, $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) FROM products WHERE slug = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$slug]);
        }
        
        return $stmt->fetchColumn() > 0;
    }
}
?>