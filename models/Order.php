<?php

declare(strict_types=1);

final class Order
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Tạo đơn từ dữ liệu giỏ hàng trong một transaction.
     * Giá và tồn kho luôn được đọc lại, khóa trực tiếp từ database.
     */
    public function createFromCart(?int $userId, array $customer, array $cartItems): int
    {
        if ($cartItems === []) {
            throw new DomainException('Giỏ hàng đang trống.');
        }

        usort(
            $cartItems,
            static fn (array $left, array $right): int => (int) $left['product_id'] <=> (int) $right['product_id']
        );

        $this->pdo->beginTransaction();

        try {
            $lockedProducts = [];
            $totalAmount = 0.0;
            $findProduct = $this->pdo->prepare(
                'SELECT id, name, price, stock, status
                 FROM products
                 WHERE id = :id
                 FOR UPDATE'
            );

            foreach ($cartItems as $cartItem) {
                $productId = (int) ($cartItem['product_id'] ?? 0);
                $quantity = (int) ($cartItem['quantity'] ?? 0);
                $findProduct->execute(['id' => $productId]);
                $product = $findProduct->fetch();

                if (!$product || (int) $product['status'] !== 1) {
                    throw new DomainException('Một sản phẩm trong giỏ không còn được bán.');
                }

                if ($quantity < 1 || (int) $product['stock'] < $quantity) {
                    throw new DomainException('Sản phẩm "' . $product['name'] . '" không đủ tồn kho.');
                }

                $price = (float) $product['price'];
                $totalAmount += $price * $quantity;
                $lockedProducts[] = [
                    'id' => (int) $product['id'],
                    'name' => (string) $product['name'],
                    'price' => $price,
                    'quantity' => $quantity,
                ];
            }

            $insertOrder = $this->pdo->prepare(
                'INSERT INTO orders
                    (user_id, customer_name, phone, address, note, total_amount, status)
                 VALUES
                    (:user_id, :customer_name, :phone, :address, :note, :total_amount, :status)'
            );
            $insertOrder->bindValue(':user_id', $userId, $userId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $insertOrder->bindValue(':customer_name', $customer['name']);
            $insertOrder->bindValue(':phone', $customer['phone']);
            $insertOrder->bindValue(':address', $customer['address']);
            $insertOrder->bindValue(':note', $customer['note'] !== '' ? $customer['note'] : null);
            $insertOrder->bindValue(':total_amount', number_format($totalAmount, 2, '.', ''));
            $insertOrder->bindValue(':status', 'pending');
            $insertOrder->execute();

            $orderId = (int) $this->pdo->lastInsertId();
            $insertItem = $this->pdo->prepare(
                'INSERT INTO order_items
                    (order_id, product_id, product_name, price, quantity)
                 VALUES
                    (:order_id, :product_id, :product_name, :price, :quantity)'
            );
            $decreaseStock = $this->pdo->prepare(
                'UPDATE products
                 SET stock = stock - :decrease_quantity
                 WHERE id = :id AND stock >= :required_quantity'
            );

            foreach ($lockedProducts as $product) {
                $insertItem->execute([
                    'order_id' => $orderId,
                    'product_id' => $product['id'],
                    'product_name' => $product['name'],
                    'price' => number_format($product['price'], 2, '.', ''),
                    'quantity' => $product['quantity'],
                ]);

                $decreaseStock->execute([
                    'id' => $product['id'],
                    'decrease_quantity' => $product['quantity'],
                    'required_quantity' => $product['quantity'],
                ]);

                if ($decreaseStock->rowCount() !== 1) {
                    throw new DomainException('Tồn kho vừa thay đổi, vui lòng kiểm tra lại giỏ hàng.');
                }
            }

            $this->pdo->commit();
            return $orderId;
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }

    public function findForUser(int $orderId, int $userId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM orders WHERE id = :id AND user_id = :user_id');
        $stmt->execute(['id' => $orderId, 'user_id' => $userId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        return $order ? $this->withItems($order) : null;
    }

    public function getForUser(int $userId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC, id DESC');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll(): array
    {
        return $this->pdo->query('SELECT o.*, u.email AS user_email FROM orders o LEFT JOIN users u ON u.id = o.user_id ORDER BY o.created_at DESC, o.id DESC')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $orderId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT o.*, u.email AS user_email FROM orders o LEFT JOIN users u ON u.id = o.user_id WHERE o.id = :id');
        $stmt->execute(['id' => $orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        return $order ? $this->withItems($order) : null;
    }

    public function updateStatus(int $orderId, string $status): bool
    {
        if (!in_array($status, ['pending', 'confirmed', 'shipping', 'completed', 'cancelled'], true)) {
            return false;
        }
        $stmt = $this->pdo->prepare('UPDATE orders SET status = :status WHERE id = :id');
        $stmt->execute(['status' => $status, 'id' => $orderId]);
        return $stmt->rowCount() === 1;
    }

    private function withItems(array $order): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM order_items WHERE order_id = :order_id ORDER BY id ASC');
        $stmt->execute(['order_id' => (int) $order['id']]);
        $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $order;
    }
}
