<?php
require_once __DIR__ . '/includes/cart.php';
require_once __DIR__ . '/includes/repository.php';

$activePage = 'cart';
$orderId = isset($_SESSION['last_order_id']) ? (int)$_SESSION['last_order_id'] : 0;
$order = null;

if ($orderId > 0 && thriftDbConfigured()) {
    try {
        $order = getOrderById($orderId);
    } catch (Throwable $e) {
        $order = null;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation — 401 Thrift</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="content">
        <h1>Order Confirmation</h1>

        <?php if (!$order): ?>
            <p class="form-status error">We couldn't find a recent order for this session.</p>
            <p><a href="shop.php" class="cta-button">Back to Shop</a></p>
        <?php else: ?>
            <p class="form-status success">
                Thanks for your order. Your confirmation number is <strong><?= htmlspecialchars($order['order_number']) ?></strong>.
            </p>

            <div class="order-summary">
                <h2>Order Details</h2>
                <div class="summary-item">
                    <span>Customer</span>
                    <span><?= htmlspecialchars($order['customer_name']) ?></span>
                </div>
                <div class="summary-item">
                    <span>Email</span>
                    <span><?= htmlspecialchars($order['customer_email']) ?></span>
                </div>
                <div class="summary-item">
                    <span>Payment Status</span>
                    <span><?= htmlspecialchars(ucwords(str_replace('_', ' ', $order['payment_status']))) ?></span>
                </div>
                <?php foreach ($order['items'] as $item): ?>
                    <div class="summary-item">
                        <span>
                            <?= htmlspecialchars($item['product_name']) ?> x<?= (int)$item['quantity'] ?>
                            <em class="cart-type-badge cart-type-<?= htmlspecialchars($item['purchase_type']) ?>">
                                <?= htmlspecialchars(ucfirst($item['purchase_type'])) ?>
                            </em>
                        </span>
                        <span>$<?= number_format((float)$item['line_total'], 2) ?></span>
                    </div>
                <?php endforeach; ?>
                <div class="order-total">
                    <p><strong>Subtotal:</strong> <span>$<?= number_format((float)$order['subtotal'], 2) ?></span></p>
                    <p><strong>Shipping:</strong> <span>$<?= number_format((float)$order['shipping'], 2) ?></span></p>
                    <p class="total-line"><strong>Total:</strong> <span>$<?= number_format((float)$order['total'], 2) ?></span></p>
                </div>
            </div>

            <p><a href="shop.php" class="cta-button">Continue Shopping</a></p>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <p>&copy; <?= date('Y') ?> 401 Thrift &mdash; Sustainable fashion, one find at a time.</p>
    </footer>
</body>
</html>
