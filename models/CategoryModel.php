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

    /**
     * Đếm số lượng sản phẩm thuộc danh mục (Dùng để kiểm tra trước khi xóa)
     */
    public function countProductsByCategoryId($categoryId) {
        $sql = "SELECT COUNT(*) FROM products WHERE category_id = :category_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':category_id' => $categoryId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Kiểm tra Slug danh mục đã tồn tại trong CSDL hay chưa
     * $excludeId dùng khi Update (bỏ qua slug của chính danh mục đang sửa)
     */
    public function isSlugExists($slug, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) FROM categories WHERE slug = :slug AND id != :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':slug' => $slug, ':id' => $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) FROM categories WHERE slug = :slug";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':slug' => $slug]);
        }
        return $stmt->fetchColumn() > 0;
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
?>