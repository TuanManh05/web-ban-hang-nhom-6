<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Sản Phẩm - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Danh Sách Sản Phẩm (Quản Trị)</h2>
            <div>
                <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-success me-2">+ Thêm sản phẩm mới</a>
                <a href="/admin" class="btn btn-secondary me-2">Bảng điều khiển</a>
                <form action="/logout" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-danger">Đăng xuất</button>
                </form>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá (VNĐ)</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($product->id); ?></td>
                                <td><strong><?php echo e($product->name); ?></strong></td>
                                <td><?php echo e($product->category->name ?? 'Mặc định'); ?></td>
                                <td><?php echo e(number_format($product->price, 0, ',', '.')); ?> đ</td>
                                <td>
                                    <a href="<?php echo e(route('admin.products.edit', $product->id)); ?>" class="btn btn-sm btn-warning me-1">Sửa giá/Tên</a>
                                    <form action="<?php echo e(route('admin.products.destroy', $product->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center">Chưa có sản phẩm nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH D:\laragon\www\web-ban-hang-nhom-6\resources\views/admin/products/index.blade.php ENDPATH**/ ?>