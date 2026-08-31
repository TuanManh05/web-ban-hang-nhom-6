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
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php?action=admin">
            <i class="bi bi-speedometer2 me-2"></i>Admin Dashboard
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <div class="ms-auto d-flex align-items-center gap-2">
                <!-- Hiển thị thông tin tài khoản Admin đang đăng nhập -->
                <?php if (isset($_SESSION['user'])): ?>
                    <span class="navbar-text text-white me-2">
                        <i class="bi bi-person-circle me-1"></i>
                        <?= htmlspecialchars($_SESSION['user']['name'] ?? $_SESSION['user']['username'] ?? 'Admin') ?>
                    </span>
                <?php endif; ?>
                
                <a href="index.php?action=home" class="btn btn-outline-light btn-sm" target="_blank">
                    <i class="bi bi-box-arrow-up-right me-1"></i>Xem trang chủ
                </a>
                <a href="index.php?action=logout" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i>Đăng xuất
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container py-2">
    <!-- Hiển thị thông báo lỗi / thành công (nếu có) -->
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($_GET['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_GET['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Tiêu đề -->
    <div class="mb-4">
        <h2 class="h3 fw-bold text-gray-800">Bảng Quản Trị Hệ Thống</h2>
        <p class="text-muted">Lựa chọn các chức năng quản lý bên dưới để bắt đầu công việc.</p>
    </div>

    <!-- Các Thẻ Điều Hướng Quản Lý Chính -->
    <div class="row g-4 mb-4">
        <!-- Quản lý Sản Phẩm -->
        <div class="col-md-6 col-lg-3">
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
        <div class="col-md-6 col-lg-3">
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

        <!-- Quản lý Đơn Hàng -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body p-4 text-center">
                    <div class="bg-warning text-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-receipt fs-3"></i>
                    </div>
                    <h5 class="card-title fw-bold">Quản lý Đơn hàng</h5>
                    <p class="card-text text-muted small">Theo dõi đơn hàng, xử lý và cập nhật trạng thái.</p>
                    <a href="index.php?action=order-index" class="btn btn-warning text-dark w-100 mt-2">
                        <i class="bi bi-arrow-right-circle me-1"></i>Truy cập Đơn hàng
                    </a>
                </div>
            </div>
        </div>

        <!-- Quản lý Người Dùng -->
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body p-4 text-center">
                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-people fs-3"></i>
                    </div>
                    <h5 class="card-title fw-bold">Nội dung / Khách hàng</h5>
                    <p class="card-text text-muted small">Quản lý người dùng và phản hồi của khách hàng.</p>
                    <a href="index.php?action=user-index" class="btn btn-info text-white w-100 mt-2">
                        <i class="bi bi-arrow-right-circle me-1"></i>Truy cập Khách hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>