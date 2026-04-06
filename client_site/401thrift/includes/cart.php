<?php
/**
 * Session-based cart for 401 Thrift.
 *
 * Cart structure stored in $_SESSION['cart']:
 *   [ 'cartKey' => ['product' => [...], 'type' => 'buy'|'bid', 'qty' => int], ... ]
 *
 * This file MUST be included before any output (it starts the session).
 * It also handles cart POST actions so every page that includes it
 * automatically handles add/update/remove/clear before rendering.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ── Pull in product data so we can look products up ───────────────────────
require_once __DIR__ . '/products.php';

// ── Handle POST actions ───────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_action'])) {
    $action   = $_POST['cart_action'];
    $redirect = $_POST['redirect'] ?? $_SERVER['PHP_SELF'];

    if ($action === 'add') {
        $productId = (int)($_POST['product_id'] ?? 0);
        $type      = $_POST['type'] === 'bid' ? 'bid' : 'buy';
        $product   = getProductById($productId);

        if ($product) {
            $key = $productId . '_' . $type;
            if (isset($_SESSION['cart'][$key])) {
                // Bids are always qty 1; buy items increment
                if ($type === 'buy') {
                    $_SESSION['cart'][$key]['qty']++;
                }
            } else {
                $_SESSION['cart'][$key] = [
                    'product' => $product,
                    'type'    => $type,
                    'qty'     => 1,
                ];
            }
        }
    }

    if ($action === 'update') {
        $key = $_POST['cart_key'] ?? '';
        $qty = (int)($_POST['qty'] ?? 0);
        if (isset($_SESSION['cart'][$key])) {
            if ($qty <= 0) {
                unset($_SESSION['cart'][$key]);
            } else {
                $_SESSION['cart'][$key]['qty'] = min($qty, 10);
            }
        }
    }

    if ($action === 'remove') {
        $key = $_POST['cart_key'] ?? '';
        unset($_SESSION['cart'][$key]);
    }

    if ($action === 'clear') {
        $_SESSION['cart'] = [];
    }

    header('Location: ' . $redirect);
    exit;
}

// ── Helper functions ──────────────────────────────────────────────────────

function cartGetAll(): array {
    return $_SESSION['cart'] ?? [];
}

function cartCount(): int {
    $count = 0;
    foreach ($_SESSION['cart'] as $entry) {
        $count += $entry['qty'];
    }
    return $count;
}

function cartSubtotal(): float {
    $sub = 0.0;
    foreach ($_SESSION['cart'] as $entry) {
        $price = $entry['type'] === 'bid'
            ? $entry['product']['bid_price']
            : $entry['product']['buy_price'];
        $sub += $price * $entry['qty'];
    }
    return $sub;
}

function cartClear(): void {
    $_SESSION['cart'] = [];
}
