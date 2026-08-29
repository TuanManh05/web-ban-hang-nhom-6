<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Hệ Thống</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow p-4" style="width: 400px; border-radius: 12px;">
        <h3 class="text-center text-primary fw-bold mb-4">ĐĂNG NHẬP</h3>

        @if($errors->any())
            <div class="alert alert-danger p-2 small text-center">{{ $errors->first() }}</div>
        @endif

        <form action="/login" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label font-weight-bold">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Email" >
            </div>
            <div class="mb-3">
                <label class="form-label font-weight-bold">Mật khẩu</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Đăng nhập</button>
        </form>
    </div>
</body>
</html>