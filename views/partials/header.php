<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Shop Logo</a>
        
        <!-- Các menu khác -->
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>
            </ul>

            <!-- Đặt nút Giỏ hàng ở đây, bên TRONG navbar -->
            <div class="d-flex">
                <?php 
                $cartCount = 0;
                if (isset($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) { $cartCount += $item['quantity']; }
                }
                ?>
                <a href="index.php?page=cart" class="btn btn-outline-dark">
                    <i class="bi bi-cart"></i> Giỏ hàng 
                    <span class="badge bg-danger rounded-pill"><?= $cartCount ?></span>
                </a>
            </div>
        </div>
    </div>
</nav>