<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Sản Phẩm - Mua Sắm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar cho Customer -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('shop') }}">CỬA HÀNG TRỰC TUYẾN</a>
            <div class="d-flex align-items-center">
                <a href="{{ route('cart.index') }}" class="btn btn-warning me-3 fw-bold">🛒 Giỏ hàng</a>
                <span class="text-white me-3">Xin chào, <strong>{{ Auth::user()->name }}</strong></span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-light btn-sm">Đăng xuất</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Danh sách sản phẩm -->
    <div class="container mt-4">
        <h3 class="mb-4 text-secondary">Danh Sách Sản Phẩm Dành Cho Bạn</h3>

        <div class="row">
            @forelse($products as $product)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-dark">{{ $product->name }}</h5>
                            <p class="text-muted small">Danh mục: {{ $product->category->name ?? 'Mặc định' }}</p>
                            <p class="text-danger fw-bold fs-5 mt-auto">{{ number_format($product->price, 0, ',', '.') }} đ</p>
                            <a href="{{ route('cart.add', $product->id) }}" class="btn btn-outline-primary w-100 mt-2">Thêm vào giỏ</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Hiện chưa có sản phẩm nào được bày bán.</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>