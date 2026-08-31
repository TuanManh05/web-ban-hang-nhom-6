<?php
class ProductModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Lấy tất cả sản phẩm kèm tên danh mục
    public function getAllProducts() {
        $sql = "SELECT p.*, c.name AS category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                ORDER BY p.id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách danh mục để hiển thị ở select box
    public function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin 1 danh mục theo ID (Dùng để kiểm tra danh mục có tồn tại hay không)
    public function getCategoryById($id) {
        $sql = "SELECT * FROM categories WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy 1 sản phẩm theo ID
    public function getProductById($id) {
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Kiểm tra trùng Slug
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

    // Thêm sản phẩm mới (Đã có description)
    public function insertProduct($data) {
        $sql = "INSERT INTO products (category_id, name, slug, price, stock, description, status) 
                VALUES (:category_id, :name, :slug, :price, :stock, :description, :status)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':category_id' => $data['category_id'],
            ':name'        => $data['name'],
            ':slug'        => $data['slug'],
            ':price'       => $data['price'],
            ':stock'       => $data['stock'],
            ':description' => $data['description'],
            ':status'      => $data['status']
        ]);
    }

    // Cập nhật sản phẩm (Đã có description)
    public function updateProduct($id, $data) {
        $sql = "UPDATE products 
                SET category_id = :category_id, 
                    name = :name, 
                    slug = :slug, 
                    price = :price, 
                    stock = :stock, 
                    description = :description, 
                    status = :status 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'          => $id,
            ':category_id' => $data['category_id'],
            ':name'        => $data['name'],
            ':slug'        => $data['slug'],
            ':price'       => $data['price'],
            ':stock'       => $data['stock'],
            ':description' => $data['description'],
            ':status'      => $data['status']
        ]);
    }

    // Xóa sản phẩm
    public function deleteProduct($id) {
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>