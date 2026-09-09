<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

final class OrderController
{
    private Order $orders;

    public function __construct(PDO $pdo)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->orders = new Order($pdo);
    }

    public function store(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            exit('Phương thức không được hỗ trợ.');
        }

        $this->verifyCsrf();

        $customer = [
            'name' => trim((string) ($_POST['customer_name'] ?? '')),
            'phone' => trim((string) ($_POST['phone'] ?? '')),
            'address' => trim((string) ($_POST['address'] ?? '')),
            'note' => trim((string) ($_POST['note'] ?? '')),
        ];
        $errors = $this->validate($customer);

        if ($errors !== []) {
            $_SESSION['checkout_errors'] = $errors;
            $_SESSION['checkout_old'] = $customer;
            $this->redirectBack(implode(' ', $errors));
        }

        $cartItems = Cart::getItems();
        if ($cartItems === []) {
            $this->redirectBack('Giỏ hàng đang trống.');
        }

        try {
            $userId = isset($_SESSION['user']['id']) ? (int) $_SESSION['user']['id'] : null;
            $orderId = $this->orders->createFromCart($userId, $customer, $cartItems);
            Cart::clear();
            unset($_SESSION['checkout_errors'], $_SESSION['checkout_old']);

            $orderCode = 'DH' . str_pad((string) $orderId, 6, '0', STR_PAD_LEFT);
            $pageTitle = 'Đặt hàng thành công';
            require __DIR__ . '/../views/order-success.php';
        } catch (DomainException $exception) {
            $this->redirectBack($exception->getMessage());
        } catch (Throwable $exception) {
            error_log($exception->__toString());
            $this->redirectBack('Không thể tạo đơn hàng lúc này. Vui lòng thử lại.');
        }
    }

    public function history(): void
    {
        AuthMiddleware::requireLogin();
        $perPage = 10;
        $totalOrders = $this->orders->countForUser((int) $_SESSION['user']['id']);
        $totalPages = max(1, (int) ceil($totalOrders / $perPage));
        $page = min($totalPages, max(1, (int) ($_GET['page'] ?? 1)));
        $orders = $this->orders->getForUser((int) $_SESSION['user']['id'], $perPage, ($page - 1) * $perPage);
        $pageTitle = 'Lịch sử đơn hàng';
        require __DIR__ . '/../views/orders/history.php';
    }

    public function detail(): void
    {
        AuthMiddleware::requireLogin();
        $order = $this->orders->findForUser(max(0, (int) ($_GET['id'] ?? 0)), (int) $_SESSION['user']['id']);
        if (!$order) { http_response_code(404); }
        $pageTitle = $order ? 'Chi tiết đơn hàng' : 'Không tìm thấy đơn hàng';
        require __DIR__ . '/../views/orders/detail.php';
    }

    public function adminIndex(): void
    {
        AuthMiddleware::requireAdmin();
        $filters = ['q' => trim((string) ($_GET['q'] ?? '')), 'status' => (string) ($_GET['status'] ?? ''), 'sort' => (string) ($_GET['sort'] ?? '')];
        $perPage = 10;
        $totalOrders = $this->orders->countSearch($filters);
        $totalPages = max(1, (int) ceil($totalOrders / $perPage));
        $page = min($totalPages, max(1, (int) ($_GET['page'] ?? 1)));
        $orders = $this->orders->search($filters, $perPage, ($page - 1) * $perPage);
        $pageTitle = 'Quản lý đơn hàng';
        require __DIR__ . '/../views/admin/orders/index.php';
    }

    public function adminDetail(): void
    {
        AuthMiddleware::requireAdmin();
        $order = $this->orders->find(max(0, (int) ($_GET['id'] ?? 0)));
        if (!$order) { http_response_code(404); }
        $pageTitle = $order ? 'Chi tiết đơn hàng' : 'Không tìm thấy đơn hàng';
        require __DIR__ . '/../views/admin/orders/detail.php';
    }

    public function updateStatus(): void
    {
        AuthMiddleware::requireAdmin();
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') { http_response_code(405); exit('Phương thức không được hỗ trợ.'); }
        $this->verifyCsrf();
        $orderId = max(0, (int) ($_POST['order_id'] ?? 0));
        $updated = $this->orders->updateStatus($orderId, (string) ($_POST['status'] ?? ''));
        $message = $updated ? 'Đã cập nhật trạng thái đơn hàng.' : 'Không thể cập nhật trạng thái đơn hàng.';
        header('Location: index.php?action=admin-order-detail&id=' . $orderId . '&' . ($updated ? 'msg=' : 'error=') . urlencode($message));
        exit;
    }

    public function cancel(): void
    {
        AuthMiddleware::requireLogin();
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') { http_response_code(405); exit('Phương thức không được hỗ trợ.'); }
        $this->verifyCsrf();
        $orderId = max(0, (int) ($_POST['order_id'] ?? 0));
        try {
            $cancelled = $this->orders->cancelForUser($orderId, (int) $_SESSION['user']['id']);
            $message = $cancelled ? 'Đã hủy đơn hàng và hoàn lại tồn kho.' : 'Chỉ có thể hủy đơn đang chờ xác nhận của bạn.';
            header('Location: index.php?action=order-detail&id=' . $orderId . '&' . ($cancelled ? 'msg=' : 'error=') . urlencode($message));
        } catch (Throwable $exception) {
            error_log($exception->__toString());
            header('Location: index.php?action=order-detail&id=' . $orderId . '&error=' . urlencode('Không thể hủy đơn lúc này.'));
        }
        exit;
    }

    private function validate(array $customer): array
    {
        $errors = [];

        if (mb_strlen($customer['name']) < 2 || mb_strlen($customer['name']) > 100) {
            $errors[] = 'Họ tên phải có từ 2 đến 100 ký tự.';
        }
        if (!preg_match('/^[0-9+().\s-]{8,20}$/', $customer['phone'])) {
            $errors[] = 'Số điện thoại không hợp lệ.';
        }
        if (mb_strlen($customer['address']) < 5 || mb_strlen($customer['address']) > 255) {
            $errors[] = 'Địa chỉ phải có từ 5 đến 255 ký tự.';
        }
        if (mb_strlen($customer['note']) > 500) {
            $errors[] = 'Ghi chú không được vượt quá 500 ký tự.';
        }

        return $errors;
    }

    private function verifyCsrf(): void
    {
        $token = (string) ($_POST['csrf_token'] ?? '');
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(419);
            exit('Phiên làm việc đã hết hạn. Vui lòng tải lại trang.');
        }
    }

    private function redirectBack(string $message): never
    {
        $checkoutView = __DIR__ . '/../views/checkout.php';
        $target = is_file($checkoutView)
            ? 'index.php?action=checkout'
            : 'views/cart.php';

        $separator = str_contains($target, '?') ? '&' : '?';

        header('Location: ' . $target . $separator . 'error=' . urlencode($message));
        exit;
    }
}
