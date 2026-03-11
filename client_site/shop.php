<?php
$activePage = 'shop';
require_once 'includes/cart.php';
require_once 'includes/products.php';

$products   = getProducts();
$categories = getCategories();

$filterCat = isset($_GET['category']) ? $_GET['category'] : 'all';
if ($filterCat !== 'all') {
    $products = array_filter($products, fn($p) => $p['category'] === $filterCat);
}

$flash = $_GET['added'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - 401 Thrift</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <div class="content">
        <h1>Shop Our Collection</h1>

        <?php if ($flash): ?>
            <p class="flash-notice">&#10003; Item added to your cart! <a href="cart.php">View cart</a></p>
        <?php endif; ?>

        <p>
            Browse our curated selection of vintage and secondhand treasures. Each item has been carefully selected
            for quality and style. Purchase at the listed price or place a bid for a chance to get an even better deal!
        </p>

        <h2>Filter by Category</h2>
        <div class="filter-section">
            <?php foreach ($categories as $cat): ?>
                <a href="shop.php<?= $cat !== 'all' ? '?category=' . urlencode($cat) : '' ?>"
                   class="filter-btn <?= $filterCat === $cat ? 'active' : '' ?>">
                    <?= $cat === 'all' ? 'All Items' : ucfirst($cat) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <h2>Available Items</h2>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card" data-category="<?= htmlspecialchars($product['category']) ?>">
                    <img
                        src="<?= htmlspecialchars($product['image']) ?>"
                        alt="<?= htmlspecialchars($product['image_alt']) ?>"
                        class="product-image">
                    <div class="product-info">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                        <p class="product-price">Buy Now: $<?= number_format($product['buy_price'], 2) ?></p>
                        <p class="product-bid">Current Bid: $<?= number_format($product['bid_price'], 2) ?></p>

                        <form method="POST" action="shop.php" style="display:inline;">
                            <input type="hidden" name="cart_action" value="add">
                            <input type="hidden" name="product_id"  value="<?= $product['id'] ?>">
                            <input type="hidden" name="type"        value="buy">
                            <input type="hidden" name="redirect"    value="shop.php?added=1">
                            <button type="submit" class="buy-btn">Buy Now</button>
                        </form>

                        <form method="POST" action="shop.php" style="display:inline;">
                            <input type="hidden" name="cart_action" value="add">
                            <input type="hidden" name="product_id"  value="<?= $product['id'] ?>">
                            <input type="hidden" name="type"        value="bid">
                            <input type="hidden" name="redirect"    value="shop.php?added=1">
                            <button type="submit" class="bid-btn">Place Bid</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="shop-info">
            <h2>How Bidding Works</h2>
            <p>
                When you place a bid on an item, you're entering a competitive auction. Bidding typically runs for
                3-7 days depending on the item. You'll be notified if you're outbid, and the highest bidder wins
                when the auction closes. Don't want to wait? Use the "Buy Now" button to purchase immediately!
            </p>
        </div>
    </div>

    <script src="js/shop.js"></script>
</body>
</html>