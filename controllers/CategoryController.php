<?php
require_once __DIR__ . '/../models/CategoryModel.php';

class CategoryController {
    private $categoryModel;

    public function __construct($pdo) {
        $this->categoryModel = new CategoryModel($pdo);
    }

    /**
     * Chuyển đổi chuỗi Tiếng Việt có dấu thành Slug chuẩn URL
     */
    private function createSlug($str) {
        $str = mb_strtolower($str, 'UTF-8');
        $unicode = [
            'a' => 'à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ',
            'e' => 'è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ',
            'i' => 'ì|í|ị|ỉ|ĩ|Ì|Í|Ị|Ỉ|Ĩ',
            'o' => 'ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ',
            'u' => 'ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ',
            'y' => 'ỳ|ý|ỵ|ỷ|ỹ|Ỳ|Ý|Ỵ|Ỷ|Ỹ',
            'd' => 'đ|Đ',
        ];
        foreach ($unicode as $nonAccent => $accent) {
            $str = preg_replace("/($accent)/i", $nonAccent, $str);
        }
        $str = preg_replace('/[^a-z0-9]+/i', '-', $str);
        return trim($str, '-');
    }

    /**
     * Sinh Slug duy nhất tránh trùng lặp trong DB
     */
    private function generateUniqueSlug($name, $excludeId = null) {
        $baseSlug = $this->createSlug($name);
        $slug = $baseSlug;
        $count = 1;

        while ($this->categoryModel->isSlugExists($slug, $excludeId)) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }

        return $slug;
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
            // Xử lý tạo slug duy nhất không trùng lặp
            $slug = $this->generateUniqueSlug($name);

            $this->categoryModel->insertCategory([
                'name' => $name,
                'slug' => $slug,
                'status' => $status
            ]);
            header("Location: index.php?action=category-index&msg=" . urlencode("Thêm danh mục thành công!"));
            exit;
        }

        require_once __DIR__ . '/../views/admin/categories/create.php';
    }

    // 3. Form chỉnh sửa danh mục
    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            header("Location: index.php?action=category-index&error=" . urlencode("Danh mục không tồn tại!"));
            exit;
        }
        require_once __DIR__ . '/../views/admin/categories/edit.php';
    }

    // 3. Cập nhật danh mục
    public function update() {
        $id = (int)($_GET['id'] ?? 0);
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            header("Location: index.php?action=category-index&error=" . urlencode("Danh mục không tồn tại!"));
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $status = (int)($_POST['status'] ?? 1);
        $errors = [];

        if (empty($name)) {
            $errors[] = "Tên danh mục không được để trống.";
        }

        if (empty($errors)) {
            // Kiểm tra và sinh slug mới nếu đổi tên, bỏ qua ID hiện tại
            $slug = ($name !== $category['name']) 
                ? $this->generateUniqueSlug($name, $id) 
                : $category['slug'];

            $this->categoryModel->updateCategory($id, [
                'name' => $name,
                'slug' => $slug,
                'status' => $status
            ]);
            header("Location: index.php?action=category-index&msg=" . urlencode("Cập nhật danh mục thành công!"));
            exit;
        }

        require_once __DIR__ . '/../views/admin/categories/edit.php';
    }

    /**
     * 4. Xóa danh mục (Chỉ nhận phương thức POST)
     */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $confirm = (int)($_POST['confirm'] ?? 0);

            if ($id > 0) {
                // Kiểm tra số lượng sản phẩm chứa trong danh mục trước khi xóa
                $productCount = $this->categoryModel->countProductsByCategoryId($id);

                // Nếu còn sản phẩm và chưa xác nhận xóa ép buộc (confirm != 1)
                if ($productCount > 0 && $confirm !== 1) {
                    header("Location: index.php?action=category-index&warning_delete_id={$id}&msg=" . urlencode("Danh mục này hiện có {$productCount} sản phẩm. Bạn có chắc chắn muốn tiếp tục xóa không?"));
                    exit;
                }

                $this->categoryModel->deleteCategory($id);
                header("Location: index.php?action=category-index&msg=" . urlencode("Xóa danh mục thành công!"));
                exit;
            } else {
                header("Location: index.php?action=category-index&error=" . urlencode("ID danh mục không hợp lệ!"));
                exit;
            }
        }

        // Nếu cố tình truy cập qua GET
        header("Location: index.php?action=category-index&error=" . urlencode("Phương thức không được hỗ trợ!"));
        exit;
    }
}
?>