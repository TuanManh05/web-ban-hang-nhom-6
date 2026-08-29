<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị - Admin Dashboard</title>
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<!-- Navbar Dashboard Admin -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php?action=admin">
            <i class="bi bi-speedometer2 me-2"></i>Admin Dashboard
        </a>
        <div class="ms-auto">
            <a href="index.php?action=home" class="btn btn-outline-light btn-sm" target="_blank">
                <i class="bi bi-box-arrow-up-right me-1"></i>Xem trang chủ
            </a>
        </div>
    </div>
</nav>

<div class="container py-2">
    <!-- Tiêu đề -->
    <div class="mb-4">
        <h2 class="h3 fw-bold text-gray-800">Bảng Quản Trị Hệ Thống</h2>
        <p class="text-muted">Lựa chọn các chức năng quản lý bên dưới để bắt đầu công việc.</p>
    </div>

    <!-- Các Thẻ Điều Hướng Quản Lý Chính -->
    <div class="row g-4 mb-4">
        <!-- Quản lý Sản Phẩm -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body p-4 text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-box-seam fs-3"></i>
                    </div>
                    <h5 class="card-title fw-bold">Quản lý Sản phẩm</h5>
                    <p class="card-text text-muted small">Xem danh sách, thêm mới, cập nhật giá và tồn kho.</p>
                    <a href="index.php?action=product-index" class="btn btn-primary w-100 mt-2">
                        <i class="bi bi-arrow-right-circle me-1"></i>Truy cập Sản phẩm
                    </a>
                </div>
            </div>
        </div>

        <!-- Quản lý Danh Mục -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body p-4 text-center">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-tags fs-3"></i>
                    </div>
                    <h5 class="card-title fw-bold">Quản lý Danh mục</h5>
                    <p class="card-text text-muted small">Phân loại sản phẩm và quản lý danh mục bánh.</p>
                    <a href="index.php?action=category-index" class="btn btn-success w-100 mt-2">
                        <i class="bi bi-arrow-right-circle me-1"></i>Truy cập Danh mục
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>