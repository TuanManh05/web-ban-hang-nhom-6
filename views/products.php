<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/ProductModel.php';

$pdo = database();
$productModel = new ProductModel($pdo);

// Đọc điều kiện tìm kiếm / lọc / sắp xếp / trang hiện tại từ URL (GET)
$keyword = trim((string) ($_GET['q'] ?? ''));
$categoryId = (int) ($_GET['category_id'] ?? 0);
$sort = (string) ($_GET['sort'] ?? '');
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 12;

$filters = [
    'q' => $keyword,
    'category_id' => $categoryId ?: null,
    'sort' => $sort,
    'limit' => $perPage,
    'offset' => ($page - 1) * $perPage,
];

// Dữ liệu tìm kiếm được xử lý an toàn: dùng prepared statement (PDO bindValue)
// trong ProductModel::searchProducts(), không nối chuỗi SQL trực tiếp.
$products = $productModel->searchProducts($filters);
$totalProducts = $productModel->countSearchProducts($filters);
$totalPages = (int) ceil($totalProducts / $perPage);

$categories = $productModel->getAllCategories();

$pageTitle = 'Sản phẩm';

require __DIR__ . '/partials/header.php';
?>
<section class="container py-5">
    <h1 class="h3 fw-bold mb-4">Sản phẩm</h1>

    <!-- Thanh tìm kiếm + lọc danh mục + sắp xếp giá -->
    <form method="get" class="row g-2 mb-4">
        <div class="col-12 col-md-5">
            <input
                type="text"
                name="q"
                class="form-control"
                placeholder="Tìm sản phẩm theo tên..."
                value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="col-6 col-md-3">
            <select name="category_id" class="form-select">
                <option value="">Tất cả danh mục</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int) $category['id'] ?>"
                        <?= $categoryId === (int) $category['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-6 col-md-3">
            <select name="sort" class="form-select">
                <option value="" <?= $sort === '' ? 'selected' : '' ?>>Mới nhất</option>
                <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
                <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
            </select>
        </div>
        <div class="col-12 col-md-1 d-grid">
            <button type="submit" class="btn btn-primary">Lọc</button>
        </div>
    </form>

    <!-- Danh sách kết quả -->
    <div class="row g-4">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card product-card h-100">
                        <a href="<?= $basePath ?>/views/product-detail.php?id=<?= urlencode((string) $product['id']) ?>"
                           class="text-decoration-none text-dark">
                            <img
                                src="<?= $product['image_path']
                                    ? htmlspecialchars($basePath . '/uploads/' . $product['image_path'], ENT_QUOTES, 'UTF-8')
                                    : $basePath . '/assets/img/product-placeholder.png' ?>"
                                class="card-img-top"
                                alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>">
                            <div class="card-body pb-2">
                                <h6 class="card-title text-truncate mb-1">
                                    <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>
                                </h6>
                                <p class="fw-bold text-danger mb-0">
                                    <?= number_format((float) $product['price'], 0, ',', '.') ?> đ
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Thông báo khi không tìm thấy sản phẩm -->
            <div class="col-12">
                <p class="text-muted text-center py-5 mb-0">
                    Không tìm thấy sản phẩm phù hợp<?= $keyword !== ''
                        ? ' với từ khoá "' . htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') . '"'
                        : '' ?>.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Phân trang, giữ nguyên điều kiện lọc khi chuyển trang -->
    <?php if ($totalPages > 1): ?>
        <nav class="mt-5">
            <ul class="pagination justify-content-center flex-wrap">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php
                        $query = http_build_query([
                            'q' => $keyword,
                            'category_id' => $categoryId ?: '',
                            'sort' => $sort,
                            'page' => $i,
                        ]);
                    ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?<?= $query ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
