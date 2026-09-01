<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bảng Điều Khiển Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Trang Quản Trị Hệ Thống (ADMIN)</h2>
            <form action="/logout" method="POST">
                <?php echo csrf_field(); ?>
                <button class="btn btn-danger">Đăng xuất (<?php echo e(Auth::user()->name); ?>)</button>
            </form>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white p-3 shadow-sm">
                    <h4>Quản lý Sản phẩm</h4>
                    <p>Thêm, sửa, xóa danh sách sản phẩm mẫu.</p>
                    <a href="/admin/products" class="btn btn-light btn-sm fw-bold">Xem Danh Sách</a>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white p-3 shadow-sm">
                    <h4>Quản lý Danh mục</h4>
                    <p>Phân loại các nhóm hàng hóa.</p>
                    <button class="btn btn-light btn-sm fw-bold" disabled>Sắp ra mắt</button>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-warning text-dark p-3 shadow-sm">
                    <h4>Quản lý Đơn hàng</h4>
                    <p>Duyệt đơn và cập nhật trạng thái.</p>
                    <button class="btn btn-dark btn-sm fw-bold" disabled>Sắp ra mắt</button>
                </div>
            </div>
        </div>
        <a href="/" class="btn btn-secondary mt-3">Quay lại Trang Chủ Bán Hàng</a>
    </div>
</body>
</html><?php /**PATH D:\laragon\www\web-ban-hang-nhom-6\resources\views/admin.blade.php ENDPATH**/ ?>