<?php require __DIR__ . '/../partials/header.php'; ?>
<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h1 class="h3 fw-bold mb-4">Đăng ký tài khoản</h1>

                    <?php if ($errors !== []): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $item): ?>
                                    <li><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?= $basePath ?>/index.php?action=register-submit">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ và tên</label>
                            <input id="name" name="name" class="form-control" required maxlength="100"
                                   value="<?= htmlspecialchars($old['name'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" name="email" class="form-control" required maxlength="150"
                                   value="<?= htmlspecialchars($old['email'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input id="password" type="password" name="password" class="form-control" required minlength="8">
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required minlength="8">
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Tạo tài khoản</button>
                    </form>

                    <p class="text-center text-secondary mt-4 mb-0">
                        Đã có tài khoản? <a href="<?= $basePath ?>/index.php?action=login">Đăng nhập</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require __DIR__ . '/../partials/footer.php'; ?>
