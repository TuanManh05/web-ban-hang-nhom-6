<?php

declare(strict_types=1);

<<<<<<< HEAD
require_once __DIR__ . '/../config/database.php';

/**
 * Xử lý tạo đơn hàng (checkout) từ giỏ hàng session.
 */
final class Order
{
    /**
     * Tạo đơn hàng mới kèm các dòng sản phẩm, đồng thời trừ tồn kho.
     *
     * @param array{name:string,phone:string,address:string,note:string} $customer
     * @param array<int, array{product_id:int,name:string,price:float,quantity:int}> $items
     * @throws \RuntimeException Khi có sản phẩm không đủ tồn kho tại thời điểm đặt hàng
     */
    public static function create(array $customer, array $items, float $total): int
    {
        if (empty($items)) {
            throw new \RuntimeException('Giỏ hàng đang trống, không thể tạo đơn hàng.');
        }

        $pdo = database();
        $pdo->beginTransaction();

        try {
            $orderStmt = $pdo->prepare(
                'INSERT INTO orders (customer_name, phone, address, note, total_amount, status)
                 VALUES (:name, :phone, :address, :note, :total, "pending")'
            );
            $orderStmt->execute([
                'name'    => $customer['name'],
                'phone'   => $customer['phone'],
                'address' => $customer['address'],
                'note'    => $customer['note'] !== '' ? $customer['note'] : null,
                'total'   => $total,
            ]);
            $orderId = (int) $pdo->lastInsertId();

            $itemStmt = $pdo->prepare(
                'INSERT INTO order_items (order_id, product_id, product_name, price, quantity)
                 VALUES (:order_id, :product_id, :product_name, :price, :quantity)'
            );

            // Chỉ trừ kho khi tồn kho hiện tại vẫn đủ (tránh vượt tồn kho do
            // dữ liệu vừa thay đổi giữa lúc xem giỏ hàng và lúc bấm đặt hàng).
            $stockStmt = $pdo->prepare(
                'UPDATE products SET stock = stock - :qty
                 WHERE id = :id AND stock >= :qty2'
            );

            foreach ($items as $item) {
                $itemStmt->execute([
                    'order_id'     => $orderId,
                    'product_id'   => $item['product_id'],
                    'product_name' => $item['name'],
                    'price'        => $item['price'],
                    'quantity'     => $item['quantity'],
                ]);

                $stockStmt->execute([
                    'qty'  => $item['quantity'],
                    'id'   => $item['product_id'],
                    'qty2' => $item['quantity'],
                ]);

                if ($stockStmt->rowCount() === 0) {
                    throw new \RuntimeException(
                        "Sản phẩm \"{$item['name']}\" vừa hết hàng hoặc không đủ số lượng tồn kho. Vui lòng cập nhật lại giỏ hàng."
                    );
                }
            }

            $pdo->commit();
            return $orderId;
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}
=======
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
}
>>>>>>> 1c8f8b4e980f257bd162376d51217f9271e39833
