<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập Quản Trị Viên (Admin)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow-lg p-4" style="width: 400px;">
        <h3 class="text-center text-danger fw-bold mb-4">ADMIN LOGIN</h3>

        @if($errors->any())
            <div class="alert alert-danger p-2 small">{{ $errors->first() }}</div>
        @endif

        <form action="/admin/login" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label text-dark">Email Admin</label>
                <input type="email" name="email" class="form-control" placeholder="admin@gmail.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-dark">Mật khẩu</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-danger w-100 fw-bold">Đăng nhập Admin</button>
        </form>
    </div>
</body>
</html>