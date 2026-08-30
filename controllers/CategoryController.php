<?php
require_once __DIR__ . '/../models/CategoryModel.php';

class CategoryController {
    private $categoryModel;

    public function __construct($pdo) {
        $this->categoryModel = new CategoryModel($pdo);
    }

    // 1. Hiển thị danh sách danh mục
    public function index() {
        $categories = $this->categoryModel->getAllCategories();
        require_once __DIR__ . '/../views/admin/categories/index.php';
    }

    // 2. Form thêm danh mục
    public function create() {
        require_once __DIR__ . '/../views/admin/categories/create.php';
    }

    // 2. Lưu danh mục mới
    public function store() {
        $name = trim($_POST['name'] ?? '');
        $status = (int)($_POST['status'] ?? 1);
        $errors = [];

        if (empty($name)) {
            $errors[] = "Tên danh mục không được để trống.";
        }

        if (empty($errors)) {
            // Tạo slug đơn giản
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
            $this->categoryModel->insertCategory([
                'name' => $name,
                'slug' => $slug,
                'status' => $status
            ]);
            header("Location: index.php?action=category-index&msg=Thêm danh mục thành công!");
            exit;
        }

        require_once __DIR__ . '/../views/admin/categories/create.php';
    }

    // 3. Form chỉnh sửa danh mục
    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            header("Location: index.php?action=category-index&error=Danh mục không tồn tại!");
            exit;
        }
        require_once __DIR__ . '/../views/admin/categories/edit.php';
    }

    // 3. Cập nhật danh mục
    public function update() {
        $id = (int)($_GET['id'] ?? 0);
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            header("Location: index.php?action=category-index&error=Danh mục không tồn tại!");
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $status = (int)($_POST['status'] ?? 1);
        $errors = [];

        if (empty($name)) {
            $errors[] = "Tên danh mục không được để trống.";
        }

        if (empty($errors)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
            $this->categoryModel->updateCategory($id, [
                'name' => $name,
                'slug' => $slug,
                'status' => $status
            ]);
            header("Location: index.php?action=category-index&msg=Cập nhật danh mục thành công!");
            exit;
        }

        require_once __DIR__ . '/../views/admin/categories/edit.php';
    }

    // 4. Xóa danh mục
    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->categoryModel->deleteCategory($id);
            header("Location: index.php?action=category-index&msg=Xóa danh mục thành công!");
        } else {
            header("Location: index.php?action=category-index&error=Danh mục không hợp lệ!");
        }
        exit;
    }
}