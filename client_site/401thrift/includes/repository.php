<?php
require_once __DIR__ . '/db.php';

function normalizeProductRow(array $row): array {
    return [
        'id' => (int)$row['id'],
        'name' => $row['name'],
        'description' => $row['description'],
        'image' => $row['image'],
        'image_alt' => $row['image_alt'],
        'buy_price' => (float)$row['buy_price'],
        'bid_price' => (float)$row['bid_price'],
        'category' => $row['category'],
        'is_active' => (int)($row['is_active'] ?? 1),
    ];
}

function fetchAllProducts(bool $includeInactive = false): array {
    $sql = 'SELECT id, name, description, image, image_alt, buy_price, bid_price, category, is_active
            FROM products';
    if (!$includeInactive) {
        $sql .= ' WHERE is_active = 1';
    }
    $sql .= ' ORDER BY id ASC';

    $stmt = thriftDb()->query($sql);
    return array_map('normalizeProductRow', $stmt->fetchAll());
}

function fetchProductById(int $id): ?array {
    $stmt = thriftDb()->prepare(
        'SELECT id, name, description, image, image_alt, buy_price, bid_price, category, is_active
         FROM products
         WHERE id = :id
         LIMIT 1'
    );
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();

    return $row ? normalizeProductRow($row) : null;
}

function fetchProductCategories(bool $includeInactive = false): array {
    $sql = 'SELECT DISTINCT category FROM products';
    if (!$includeInactive) {
        $sql .= ' WHERE is_active = 1';
    }
    $sql .= ' ORDER BY category ASC';

    $stmt = thriftDb()->query($sql);
    return array_map(static fn (array $row) => $row['category'], $stmt->fetchAll());
}

function createProduct(array $payload): int {
    $stmt = thriftDb()->prepare(
        'INSERT INTO products (name, description, image, image_alt, buy_price, bid_price, category, is_active)
         VALUES (:name, :description, :image, :image_alt, :buy_price, :bid_price, :category, :is_active)'
    );
    $stmt->execute([
        ':name' => $payload['name'],
        ':description' => $payload['description'],
        ':image' => $payload['image'],
        ':image_alt' => $payload['image_alt'],
        ':buy_price' => $payload['buy_price'],
        ':bid_price' => $payload['bid_price'],
        ':category' => $payload['category'],
        ':is_active' => $payload['is_active'],
    ]);

    return (int)thriftDb()->lastInsertId();
}

function updateProductRecord(int $id, array $payload): void {
    $stmt = thriftDb()->prepare(
        'UPDATE products
         SET name = :name,
             description = :description,
             image = :image,
             image_alt = :image_alt,
             buy_price = :buy_price,
             bid_price = :bid_price,
             category = :category,
             is_active = :is_active
         WHERE id = :id'
    );
    $stmt->execute([
        ':id' => $id,
        ':name' => $payload['name'],
        ':description' => $payload['description'],
        ':image' => $payload['image'],
        ':image_alt' => $payload['image_alt'],
        ':buy_price' => $payload['buy_price'],
        ':bid_price' => $payload['bid_price'],
        ':category' => $payload['category'],
        ':is_active' => $payload['is_active'],
    ]);
}

function deleteProductRecord(int $id): void {
    $stmt = thriftDb()->prepare('DELETE FROM products WHERE id = :id');
    $stmt->execute([':id' => $id]);
}

function saveContactMessage(array $payload): int {
    $stmt = thriftDb()->prepare(
        'INSERT INTO contact_messages (name, email, subject, message, created_at)
         VALUES (:name, :email, :subject, :message, :created_at)'
    );
    $stmt->execute([
        ':name' => $payload['name'],
        ':email' => $payload['email'],
        ':subject' => $payload['subject'],
        ':message' => $payload['message'],
        ':created_at' => date('Y-m-d H:i:s'),
    ]);

    return (int)thriftDb()->lastInsertId();
}

function createOrderRecord(array $customer, array $cartItems, float $subtotal, float $shipping, float $total, string $paymentStatus, string $paymentReference = ''): array {
    $pdo = thriftDb();
    $pdo->beginTransaction();

    try {
        $orderNumber = '401-' . strtoupper(bin2hex(random_bytes(4)));

        $orderStmt = $pdo->prepare(
            'INSERT INTO orders (
                order_number, customer_name, customer_email, address_line1, address_line2,
                city, state, postal_code, subtotal, shipping, total, payment_status,
                payment_reference, created_at
            ) VALUES (
                :order_number, :customer_name, :customer_email, :address_line1, :address_line2,
                :city, :state, :postal_code, :subtotal, :shipping, :total, :payment_status,
                :payment_reference, :created_at
            )'
        );
        $orderStmt->execute([
            ':order_number' => $orderNumber,
            ':customer_name' => $customer['name'],
            ':customer_email' => $customer['email'],
            ':address_line1' => $customer['address_line1'],
            ':address_line2' => $customer['address_line2'],
            ':city' => $customer['city'],
            ':state' => $customer['state'],
            ':postal_code' => $customer['postal_code'],
            ':subtotal' => $subtotal,
            ':shipping' => $shipping,
            ':total' => $total,
            ':payment_status' => $paymentStatus,
            ':payment_reference' => $paymentReference,
            ':created_at' => date('Y-m-d H:i:s'),
        ]);

        $orderId = (int)$pdo->lastInsertId();

        $itemStmt = $pdo->prepare(
            'INSERT INTO order_items (
                order_id, product_id, product_name, purchase_type, unit_price, quantity, line_total
            ) VALUES (
                :order_id, :product_id, :product_name, :purchase_type, :unit_price, :quantity, :line_total
            )'
        );

        foreach ($cartItems as $entry) {
            $product = $entry['product'];
            $unitPrice = $entry['type'] === 'bid' ? $product['bid_price'] : $product['buy_price'];

            $itemStmt->execute([
                ':order_id' => $orderId,
                ':product_id' => (int)$product['id'],
                ':product_name' => $product['name'],
                ':purchase_type' => $entry['type'],
                ':unit_price' => $unitPrice,
                ':quantity' => (int)$entry['qty'],
                ':line_total' => $unitPrice * (int)$entry['qty'],
            ]);
        }

        $pdo->commit();

        return [
            'id' => $orderId,
            'order_number' => $orderNumber,
        ];
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

function getOrderById(int $orderId): ?array {
    $pdo = thriftDb();
    $orderStmt = $pdo->prepare('SELECT * FROM orders WHERE id = :id');
    $orderStmt->execute([':id' => $orderId]);
    $order = $orderStmt->fetch();

    if (!$order) {
        return null;
    }

    $itemsStmt = $pdo->prepare('SELECT * FROM order_items WHERE order_id = :order_id ORDER BY id ASC');
    $itemsStmt->execute([':order_id' => $orderId]);
    $order['items'] = $itemsStmt->fetchAll();

    return $order;
}
