<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Thông Tin Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 600px;">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h4>Sửa Sản Phẩm</h4>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('admin.products.update', $product->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="mb-3">
                        <label class="form-label">Tên sản phẩm:</label>
                        <input type="text" name="name" class="form-control" value="<?php echo e($product->name); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Danh mục:</label>
                        <select name="category_id" class="form-control" required>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e($product->category_id == $category->id ? 'selected' : ''); ?>>
                                    <?php echo e($category->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giá (VNĐ):</label>
                        <input type="number" name="price" class="form-control" value="<?php echo e($product->price); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-secondary">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH D:\laragon\www\web-ban-hang-nhom-6\resources\views/admin/products/edit.blade.php ENDPATH**/ ?>