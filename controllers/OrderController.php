<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Order.php';

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
