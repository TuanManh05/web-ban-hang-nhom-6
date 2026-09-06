<?php

declare(strict_types=1);

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