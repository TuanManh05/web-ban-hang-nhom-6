<?php
require_once __DIR__ . '/../models/ProductModel.php';

class ProductController {
    private $productModel;

    public function __construct($pdo) {
        // 1. Khởi tạo session nếu chưa khởi chạy
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Kiểm tra đăng nhập và phân quyền Admin (SHOP-9 & SHOP-10)
        $this->checkAdminAuth();

        $this->productModel = new ProductModel($pdo);
    }

    /**
     * Middleware kiểm tra quyền Admin
     */
    private function checkAdminAuth() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login&error=" . urlencode("Vui lòng đăng nhập để tiếp tục!"));
            exit;
        }

        $userRole = $_SESSION['user']['role'] ?? '';
        if ($userRole !== 'admin' && $userRole != 1) {
            header("Location: index.php?error=" . urlencode("Bạn không có quyền truy cập trang quản trị!"));
            exit;
        }
    }

    private function createSlug($str) {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }

    private function generateUniqueSlug($name, $excludeId = null) {
        $baseSlug = $this->createSlug($name);
        $slug = $baseSlug;
        $count = 1;

        while ($this->productModel->isSlugExists($slug, $excludeId)) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Hiển thị danh sách sản phẩm (Read)
     */
    public function index() {
        $products = $this->productModel->getAllProducts();
        require_once __DIR__ . '/../views/admin/products/index.php';
    }

    /**
     * Hiển thị Form thêm mới
     */
    public function create() {
        $categories = $this->productModel->getAllCategories();
        require_once __DIR__ . '/../views/admin/products/create.php';
    }

    /**
     * Xử lý lưu sản phẩm
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name        = trim($_POST['name'] ?? '');
            $category_id = (int)($_POST['category_id'] ?? 0);
            $price       = $_POST['price'] ?? '';
            $stock       = $_POST['stock'] ?? '';
            $description = trim($_POST['description'] ?? '');
            $status      = isset($_POST['status']) ? (int)$_POST['status'] : 1;

            $errors = [];

            if (empty($name)) {
                $errors[] = "Tên sản phẩm không được để trống.";
            }

            // Kiểm tra danh mục hợp lệ và tồn tại trong DB
            if ($category_id <= 0) {
                $errors[] = "Vui lòng chọn danh mục hợp lệ.";
            } else {
                $categoryExists = $this->productModel->getCategoryById($category_id);
                if (!$categoryExists) {
                    $errors[] = "Danh mục được chọn không tồn tại trong hệ thống.";
                }
            }

            if (!is_numeric($price) || (float)$price <= 0) {
                $errors[] = "Giá sản phẩm phải là số và lớn hơn 0.";
            }
            if (!filter_var($stock, FILTER_VALIDATE_INT) && $stock !== '0') {
                $errors[] = "Số lượng phải là số nguyên.";
            } elseif ((int)$stock < 0) {
                $errors[] = "Số lượng phải lớn hơn hoặc bằng 0.";
            }

            if (!empty($errors)) {
                $categories = $this->productModel->getAllCategories();
                require_once __DIR__ . '/../views/admin/products/create.php';
                return;
            }

            $slug = $this->generateUniqueSlug($name);

            $data = [
                'category_id' => $category_id,
                'name'        => $name,
                'slug'        => $slug,
                'price'       => (float)$price,
                'stock'       => (int)$stock,
                'description' => $description,
                'status'      => $status
            ];

            $this->productModel->insertProduct($data);
            header("Location: index.php?action=product-index&msg=" . urlencode("Thêm sản phẩm thành công!"));
            exit;
        }
    }

    /**
     * Hiển thị Form sửa
     */
    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            header("Location: index.php?action=product-index&error=" . urlencode("Sản phẩm không tồn tại!"));
            exit;
        }

        $categories = $this->productModel->getAllCategories();
        require_once __DIR__ . '/../views/admin/products/edit.php';
    }

    /**
     * Xử lý cập nhật sản phẩm
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id          = (int)($_GET['id'] ?? 0);
            $product     = $this->productModel->getProductById($id);

            if (!$product) {
                header("Location: index.php?action=product-index&error=" . urlencode("Sản phẩm không tồn tại!"));
                exit;
            }

            $name        = trim($_POST['name'] ?? '');
            $category_id = (int)($_POST['category_id'] ?? 0);
            $price       = $_POST['price'] ?? '';
            $stock       = $_POST['stock'] ?? '';
            $description = trim($_POST['description'] ?? '');
            $status      = isset($_POST['status']) ? (int)$_POST['status'] : 1;

            $errors = [];

            if (empty($name)) {
                $errors[] = "Tên sản phẩm không được để trống.";
            }

            // Kiểm tra danh mục hợp lệ và tồn tại trong DB
            if ($category_id <= 0) {
                $errors[] = "Vui lòng chọn danh mục hợp lệ.";
            } else {
                $categoryExists = $this->productModel->getCategoryById($category_id);
                if (!$categoryExists) {
                    $errors[] = "Danh mục được chọn không tồn tại trong hệ thống.";
                }
            }

            if (!is_numeric($price) || (float)$price <= 0) {
                $errors[] = "Giá sản phẩm phải là số và lớn hơn 0.";
            }
            if (!filter_var($stock, FILTER_VALIDATE_INT) && $stock !== '0') {
                $errors[] = "Số lượng phải là số nguyên.";
            } elseif ((int)$stock < 0) {
                $errors[] = "Số lượng phải lớn hơn hoặc bằng 0.";
            }

            if (!empty($errors)) {
                $categories = $this->productModel->getAllCategories();
                require_once __DIR__ . '/../views/admin/products/edit.php';
                return;
            }

            $slug = ($name !== $product['name']) 
                ? $this->generateUniqueSlug($name, $id) 
                : $product['slug'];

            $data = [
                'category_id' => $category_id,
                'name'        => $name,
                'slug'        => $slug,
                'price'       => (float)$price,
                'stock'       => (int)$stock,
                'description' => $description,
                'status'      => $status
            ];

            $this->productModel->updateProduct($id, $data);
            header("Location: index.php?action=product-index&msg=" . urlencode("Cập nhật sản phẩm thành công!"));
            exit;
        }
    }

    /**
     * Xóa sản phẩm (Chỉ nhận POST)
     */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            
            if ($id > 0) {
                $this->productModel->deleteProduct($id);
                header("Location: index.php?action=product-index&msg=" . urlencode("Xóa sản phẩm thành công!"));
                exit;
            } else {
                header("Location: index.php?action=product-index&error=" . urlencode("ID sản phẩm không hợp lệ!"));
                exit;
            }
        }

        header("Location: index.php?action=product-index&error=" . urlencode("Phương thức không được hỗ trợ!"));
        exit;
    }
}
?>