<?php
declare(strict_types=1);

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../models/Order.php';
require __DIR__ . '/../models/User.php';
require __DIR__ . '/../models/ProductModel.php';

$pdo = database();
$databaseName = (string) $pdo->query('SELECT DATABASE()')->fetchColumn();
if (!str_ends_with($databaseName, '_test')) {
    throw new RuntimeException('Chỉ được chạy kiểm thử trên database có hậu tố _test.');
}

function check(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
    echo "PASS: {$message}\n";
}

$customer = $pdo->query("SELECT * FROM users WHERE role = 'customer' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$product = $pdo->query('SELECT * FROM products WHERE status = 1 AND stock >= 2 LIMIT 1')->fetch(PDO::FETCH_ASSOC);
check((bool) $customer, 'Có tài khoản customer mẫu');
check((bool) $product, 'Có sản phẩm đủ tồn kho');

$orders = new Order($pdo);
$stockBefore = (int) $product['stock'];
$customerData = ['name' => 'Khách kiểm thử', 'phone' => '0900000000', 'address' => 'Địa chỉ kiểm thử', 'note' => ''];
$cart = [['product_id' => (int) $product['id'], 'quantity' => 1]];
$orderId = $orders->createFromCart((int) $customer['id'], $customerData, $cart);
check($orderId > 0, 'Tạo đơn hàng thành công');
check((int) $pdo->query('SELECT stock FROM products WHERE id = ' . (int) $product['id'])->fetchColumn() === $stockBefore - 1, 'Tạo đơn đã trừ tồn kho');
check($orders->findForUser($orderId, (int) $customer['id']) !== null, 'Chủ đơn xem được chi tiết đơn');
check($orders->findForUser($orderId, 999999) === null, 'Tài khoản khác không xem được đơn');
check($orders->cancelForUser($orderId, (int) $customer['id']), 'Khách hủy được đơn pending của mình');
check((int) $pdo->query('SELECT stock FROM products WHERE id = ' . (int) $product['id'])->fetchColumn() === $stockBefore, 'Hủy đơn đã hoàn lại tồn kho');
check(!$orders->cancelForUser($orderId, (int) $customer['id']), 'Không thể hủy lại đơn đã hủy');

$orderId2 = $orders->createFromCart((int) $customer['id'], $customerData, $cart);
check($orders->updateStatus($orderId2, 'confirmed'), 'Admin chuyển pending sang confirmed');
check(!$orders->updateStatus($orderId2, 'completed'), 'Chặn chuyển confirmed thẳng sang completed');
check($orders->updateStatus($orderId2, 'shipping'), 'Admin chuyển confirmed sang shipping');
check($orders->updateStatus($orderId2, 'completed'), 'Admin chuyển shipping sang completed');
check(!$orders->cancelForUser($orderId2, (int) $customer['id']), 'Khách không hủy được đơn completed');

$results = $orders->search(['q' => 'Khách kiểm thử', 'status' => '', 'sort' => ''], 10, 0);
check(count($results) >= 2, 'Tìm đơn theo tên khách hàng');
check($orders->countForUser((int) $customer['id']) >= 2, 'Đếm đơn phục vụ phân trang khách hàng');

$products = (new ProductModel($pdo))->searchProducts(['q' => (string) $product['name'], 'category_id' => null, 'sort' => 'price_asc', 'limit' => 12, 'offset' => 0]);
check($products !== [], 'Tìm kiếm và sắp xếp sản phẩm');

$users = new User($pdo);
$users->updateName((int) $customer['id'], 'Khách đã cập nhật');
check($users->findById((int) $customer['id'])['name'] === 'Khách đã cập nhật', 'Cập nhật tên tài khoản');
$users->updatePassword((int) $customer['id'], 'MatKhauMoi123');
check(password_verify('MatKhauMoi123', (string) $users->findById((int) $customer['id'])['password']), 'Đổi và mã hóa mật khẩu');

echo "INTEGRATION TESTS PASSED\n";
