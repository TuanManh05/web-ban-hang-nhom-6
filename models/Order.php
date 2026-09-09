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

    public function getForUser(int $userId, int $limit = 10, int $offset = 0): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countForUser(int $userId): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM orders WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        return (int) $stmt->fetchColumn();
    }

    public function search(array $filters, int $limit = 10, int $offset = 0): array
    {
        [$where, $params] = $this->buildSearchWhere($filters);
        $sortMap = ['oldest' => 'o.created_at ASC', 'total_asc' => 'o.total_amount ASC', 'total_desc' => 'o.total_amount DESC'];
        $orderBy = $sortMap[$filters['sort'] ?? ''] ?? 'o.created_at DESC';
        $sql = 'SELECT o.*, u.email AS user_email FROM orders o LEFT JOIN users u ON u.id = o.user_id WHERE ' . implode(' AND ', $where) . " ORDER BY $orderBy, o.id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) { $stmt->bindValue($key, $value); }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countSearch(array $filters): int
    {
        [$where, $params] = $this->buildSearchWhere($filters);
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM orders o WHERE ' . implode(' AND ', $where));
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
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
        $allowedTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['shipping', 'cancelled'],
            'shipping' => ['completed'],
            'completed' => [],
            'cancelled' => [],
        ];
        return $this->transitionStatus($orderId, $status, null, $allowedTransitions);
    }

    public function cancelForUser(int $orderId, int $userId): bool
    {
        return $this->transitionStatus($orderId, 'cancelled', $userId, ['pending' => ['cancelled']]);
    }

    private function withItems(array $order): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM order_items WHERE order_id = :order_id ORDER BY id ASC');
        $stmt->execute(['order_id' => (int) $order['id']]);
        $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $order;
    }

    private function transitionStatus(int $orderId, string $newStatus, ?int $userId, array $allowedTransitions): bool
    {
        $this->pdo->beginTransaction();
        try {
            $sql = 'SELECT id, status FROM orders WHERE id = :id' . ($userId === null ? '' : ' AND user_id = :user_id') . ' FOR UPDATE';
            $stmt = $this->pdo->prepare($sql);
            $params = ['id' => $orderId];
            if ($userId !== null) { $params['user_id'] = $userId; }
            $stmt->execute($params);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$order || !in_array($newStatus, $allowedTransitions[$order['status']] ?? [], true)) {
                $this->pdo->rollBack();
                return false;
            }
            if ($newStatus === 'cancelled') {
                $items = $this->pdo->prepare('SELECT product_id, quantity FROM order_items WHERE order_id = :order_id');
                $items->execute(['order_id' => $orderId]);
                $restore = $this->pdo->prepare('UPDATE products SET stock = stock + :quantity WHERE id = :id');
                foreach ($items->fetchAll(PDO::FETCH_ASSOC) as $item) {
                    if ($item['product_id'] !== null) { $restore->execute(['quantity' => (int) $item['quantity'], 'id' => (int) $item['product_id']]); }
                }
            }
            $update = $this->pdo->prepare('UPDATE orders SET status = :status WHERE id = :id');
            $update->execute(['status' => $newStatus, 'id' => $orderId]);
            $this->pdo->commit();
            return true;
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) { $this->pdo->rollBack(); }
            throw $exception;
        }
    }

    private function buildSearchWhere(array $filters): array
    {
        $where = ['1 = 1'];
        $params = [];
        $keyword = trim((string) ($filters['q'] ?? ''));
        if ($keyword !== '') {
            $value = '%' . preg_replace('/^DH0*/i', '', $keyword) . '%';
            $where[] = "(CAST(o.id AS CHAR) LIKE :keyword_id OR o.customer_name LIKE :keyword_name OR o.phone LIKE :keyword_phone)";
            $params[':keyword_id'] = $value;
            $params[':keyword_name'] = $value;
            $params[':keyword_phone'] = $value;
        }
        $status = (string) ($filters['status'] ?? '');
        if (in_array($status, ['pending', 'confirmed', 'shipping', 'completed', 'cancelled'], true)) {
            $where[] = 'o.status = :status';
            $params[':status'] = $status;
        }
        return [$where, $params];
    }
}
