<?php
require_once __DIR__ . '/../includes/cart.php';
require_once __DIR__ . '/../includes/repository.php';
require_once __DIR__ . '/../includes/api.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'error' => 'Method not allowed.'], 405);
}

if (!thriftDbConfigured()) {
    jsonResponse([
        'success' => false,
        'error' => 'Database is not configured. Update db/config.php and import db/schema.sql on your cPanel host.'
    ], 500);
}

$cartItems = cartGetAll();
if (empty($cartItems)) {
    jsonResponse(['success' => false, 'error' => 'Your cart is empty.'], 400);
}

$payload = readJsonInput();
$errors = validateCheckoutPayload($payload);

if ($errors) {
    jsonResponse(['success' => false, 'errors' => $errors], 422);
}

$subtotal = cartSubtotal();
$shipping = count($cartItems) > 0 ? 5.99 : 0.00;
$total = $subtotal + $shipping;
$paymentMethodId = trim((string)($payload['payment_method_id'] ?? ''));
$paymentStatus = $paymentMethodId !== '' ? 'payment_method_received' : 'demo_submitted';

try {
    $order = createOrderRecord(
        [
            'name' => trim($payload['name']),
            'email' => trim($payload['email']),
            'address_line1' => trim($payload['address_line1']),
            'address_line2' => trim($payload['address_line2'] ?? ''),
            'city' => trim($payload['city']),
            'state' => trim($payload['state']),
            'postal_code' => trim($payload['postal_code']),
        ],
        $cartItems,
        $subtotal,
        $shipping,
        $total,
        $paymentStatus,
        $paymentMethodId
    );

    $_SESSION['last_order_id'] = $order['id'];
    $_SESSION['last_order_number'] = $order['order_number'];
    cartClear();

    jsonResponse([
        'success' => true,
        'order_id' => $order['id'],
        'order_number' => $order['order_number'],
        'redirect_url' => 'order-confirmation.php',
    ]);
} catch (Throwable $e) {
    jsonResponse([
        'success' => false,
        'error' => 'Unable to save your order right now. Please try again.'
    ], 500);
}
