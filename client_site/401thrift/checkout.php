<?php
$activePage = 'cart';
require_once 'includes/cart.php';
require_once 'includes/products.php';

$cartItems = cartGetAll();
$subtotal  = cartSubtotal();
$shipping  = count($cartItems) > 0 ? 5.99 : 0.00;
$total     = $subtotal + $shipping;

// Redirect to cart if empty
if (empty($cartItems)) {
    header('Location: cart.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout — 401 Thrift</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <div class="content">
        <h1>Checkout</h1>

        <div class="checkout-container">

            <!-- Order Summary -->
            <div class="order-summary">
                <h2>Order Summary</h2>
                <div id="cart-items">
                    <?php foreach ($cartItems as $entry): ?>
                        <?php
                            $p     = $entry['product'];
                            $price = $entry['type'] === 'bid' ? $p['bid_price'] : $p['buy_price'];
                            $line  = $price * $entry['qty'];
                        ?>
                        <div class="summary-item">
                            <span>
                                <?= htmlspecialchars($p['name']) ?>
                                <?= $entry['qty'] > 1 ? ' &times;' . $entry['qty'] : '' ?>
                                <em class="cart-type-badge cart-type-<?= $entry['type'] ?>">
                                    <?= $entry['type'] === 'bid' ? 'Bid' : 'Buy' ?>
                                </em>
                            </span>
                            <span>$<?= number_format($line, 2) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="order-total">
                    <p><strong>Subtotal:</strong> <span>$<?= number_format($subtotal, 2) ?></span></p>
                    <p><strong>Shipping:</strong> <span>$<?= number_format($shipping, 2) ?></span></p>
                    <p class="total-line"><strong>Total:</strong> <span>$<?= number_format($total, 2) ?></span></p>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="payment-form">
                <h2>Payment Information</h2>
                <?php $isStripeConfigured = !str_contains('pk_test_YOUR_KEY_HERE', 'YOUR_KEY_HERE'); ?>
                <?php if (!$isStripeConfigured): ?>
                    <p class="payment-note">
                        Demo checkout is active. Enter the form details and submit to simulate a successful payment.
                    </p>
                <?php endif; ?>

                <form id="payment-form">
                    <div class="form-section">
                        <h3>Contact Information</h3>
                        <div class="form-group">
                            <label for="customer-name">Full Name *</label>
                            <input type="text" id="customer-name" autocomplete="name" required>
                        </div>
                        <div class="form-group">
                            <label for="customer-email">Email *</label>
                            <input type="email" id="customer-email" autocomplete="email" required>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Shipping Address</h3>
                        <div class="form-group">
                            <label for="address-line1">Address Line 1 *</label>
                            <input type="text" id="address-line1" autocomplete="address-line1" required>
                        </div>
                        <div class="form-group">
                            <label for="address-line2">Address Line 2</label>
                            <input type="text" id="address-line2" autocomplete="address-line2">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City *</label>
                                <input type="text" id="city" autocomplete="address-level2" required>
                            </div>
                            <div class="form-group">
                                <label for="state">State *</label>
                                <input type="text" id="state" autocomplete="address-level1" required>
                            </div>
                            <div class="form-group">
                                <label for="zip">ZIP *</label>
                                <input type="text" id="zip" autocomplete="postal-code" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Card Information</h3>
                        <div class="form-group">
                            <label for="card-element">Credit or Debit Card *</label>
                            <div id="card-element" class="stripe-element"></div>
                            <div id="card-errors" role="alert"></div>
                        </div>
                    </div>

                    <!-- Pass total to JS -->
                    <input type="hidden" id="order-total-cents" value="<?= (int)round($total * 100) ?>">

                    <button type="submit" id="submit-payment" class="payment-btn">
                        <span id="button-text">Pay $<?= number_format($total, 2) ?></span>
                        <span id="spinner" class="spinner hidden"></span>
                    </button>

                    <div id="payment-message" class="payment-message"></div>
                </form>
            </div>

        </div>

        <div class="security-info">
            <p>🔒 <strong>Secure Payment:</strong> Your payment information is encrypted and secure.
               We use Stripe for payment processing and never store your card details.</p>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; <?= date('Y') ?> 401 Thrift &mdash; Sustainable fashion, one find at a time.</p>
    </footer>

    <script src="js/config.js"></script>
    <script src="js/checkout.js"></script>
</body>
</html>
