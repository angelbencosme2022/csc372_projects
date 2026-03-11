<?php
$activePage = 'cart';
require_once 'includes/cart.php';   // starts session, handles POST actions
require_once 'includes/products.php';

$cartItems = cartGetAll();
$subtotal  = cartSubtotal();
$shipping  = count($cartItems) > 0 ? 5.99 : 0.00;
$total     = $subtotal + $shipping;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - 401 Thrift</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <div class="content">
        <h1>Your Cart</h1>

        <?php if (empty($cartItems)): ?>
            <div class="cart-empty">
                <p>Your cart is empty. <a href="shop.php">Start shopping!</a></p>
            </div>

        <?php else: ?>

            <div class="cart-container">
                <!-- Cart Items -->
                <div class="cart-items-list">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Line Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $key => $entry): ?>
                                <?php
                                    $p     = $entry['product'];
                                    $type  = $entry['type'];
                                    $qty   = $entry['qty'];
                                    $price = $type === 'bid' ? $p['bid_price'] : $p['buy_price'];
                                ?>
                                <tr class="cart-row">
                                    <td class="cart-item-info">
                                        <img src="<?= htmlspecialchars($p['image']) ?>"
                                             alt="<?= htmlspecialchars($p['image_alt']) ?>"
                                             class="cart-thumb">
                                        <span><?= htmlspecialchars($p['name']) ?></span>
                                    </td>
                                    <td>
                                        <span class="cart-type-badge cart-type-<?= $type ?>">
                                            <?= $type === 'bid' ? 'Bid' : 'Buy' ?>
                                        </span>
                                    </td>
                                    <td>$<?= number_format($price, 2) ?></td>

                                    <!-- Qty update -->
                                    <td>
                                        <?php if ($type === 'buy'): ?>
                                            <form method="POST" action="cart.php" class="qty-form">
                                                <input type="hidden" name="cart_action" value="update">
                                                <input type="hidden" name="cart_key"    value="<?= htmlspecialchars($key) ?>">
                                                <input type="hidden" name="redirect"    value="cart.php">
                                                <input type="number" name="qty"
                                                       value="<?= $qty ?>" min="0" max="10"
                                                       class="qty-input"
                                                       onchange="this.form.submit()">
                                            </form>
                                        <?php else: ?>
                                            <span class="qty-fixed">1</span>
                                            <small>(one bid)</small>
                                        <?php endif; ?>
                                    </td>

                                    <td>$<?= number_format($price * $qty, 2) ?></td>

                                    <!-- Remove -->
                                    <td>
                                        <form method="POST" action="cart.php">
                                            <input type="hidden" name="cart_action" value="remove">
                                            <input type="hidden" name="cart_key"    value="<?= htmlspecialchars($key) ?>">
                                            <input type="hidden" name="redirect"    value="cart.php">
                                            <button type="submit" class="remove-btn" title="Remove item">&#x2715;</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Clear cart -->
                    <form method="POST" action="cart.php" class="clear-cart-form">
                        <input type="hidden" name="cart_action" value="clear">
                        <input type="hidden" name="redirect"    value="cart.php">
                        <button type="submit" class="clear-btn"
                                onclick="return confirm('Remove all items from cart?')">
                            Clear Cart
                        </button>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="cart-summary">
                    <h2>Order Summary</h2>
                    <div class="summary-line">
                        <span>Subtotal</span>
                        <span>$<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="summary-line">
                        <span>Shipping</span>
                        <span>$<?= number_format($shipping, 2) ?></span>
                    </div>
                    <div class="summary-line summary-total">
                        <strong>Total</strong>
                        <strong>$<?= number_format($total, 2) ?></strong>
                    </div>

                    <a href="checkout.php" class="cta-button checkout-link">Proceed to Checkout</a>
                    <a href="shop.php" class="continue-shopping">&#8592; Continue Shopping</a>
                </div>
            </div>

        <?php endif; ?>
    </div>

</body>
</html>