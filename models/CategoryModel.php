<?php
class CategoryModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Lấy tất cả danh mục
    public function getAllCategories() {
        $stmt = $this->pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin 1 danh mục theo ID
    public function getCategoryById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isSlugExists($slug, $excludeId = null) {
        if ($excludeId !== null) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM categories WHERE slug = :slug AND id != :id");
            $stmt->execute([':slug' => $slug, ':id' => $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM categories WHERE slug = :slug");
            $stmt->execute([':slug' => $slug]);
        }

        return (int)$stmt->fetchColumn() > 0;
    }

    public function hasProducts($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = :id");
        $stmt->execute([':id' => $id]);
        return (int)$stmt->fetchColumn() > 0;
    }

    // Thêm mới danh mục
    public function insertCategory($data) {
        $sql = "INSERT INTO categories (name, slug, status) VALUES (:name, :slug, :status)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name'   => $data['name'],
            ':slug'   => $data['slug'],
            ':status' => $data['status']
        ]);
    }

    // Cập nhật danh mục
    public function updateCategory($id, $data) {
        $sql = "UPDATE categories SET name = :name, slug = :slug, status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'     => $id,
            ':name'   => $data['name'],
            ':slug'   => $data['slug'],
            ':status' => $data['status']
        ]);
    }

    // Xóa danh mục
    public function deleteCategory($id) {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
