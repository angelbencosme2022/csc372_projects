<?php
$activePage = 'shop';
require_once 'includes/cart.php';
require_once 'includes/products.php';

$products = getProducts();
$categories = array_filter(getCategories(), static fn ($category) => $category !== 'all');
$usesDatabase = thriftDbConfigured();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop — 401 Thrift</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <div class="content">
        <h1>Shop Our Collection</h1>

        <p>
            Browse our curated selection of vintage and secondhand treasures. Each item has been carefully selected
            for quality and style. Purchase at the listed price or place a bid for a chance to get an even better deal!
        </p>
        <?php if ($usesDatabase): ?>
            <p class="inventory-callout">
                Product inventory is database-backed. Add, edit, or remove clothes from
                <a href="admin-products.php">Inventory Manager</a>.
            </p>
        <?php else: ?>
            <p class="form-status error">
                Database not configured yet. The shop is showing fallback sample products until you update
                `includes/database-connection.php` with valid MySQL credentials.
            </p>
        <?php endif; ?>

        <h2>Filter by Category</h2>
        <div class="filter-section">
            <button class="filter-btn active" data-filter="all">All Items</button>
            <?php foreach ($categories as $category): ?>
                <button class="filter-btn" data-filter="<?= htmlspecialchars($category) ?>">
                    <?= htmlspecialchars(ucfirst($category)) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <h2>Available Items</h2>
        <div class="products-grid" id="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card" data-category="<?= htmlspecialchars($product['category']) ?>">
                    <img src="<?= htmlspecialchars($product['image']) ?>"
                         alt="<?= htmlspecialchars($product['image_alt']) ?>"
                         class="product-image"
                         onerror="this.style.display='none'">
                    <div class="product-info">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                        <p class="product-price">Buy Now: $<?= number_format($product['buy_price'], 2) ?></p>
                        <p class="product-bid">Current Bid: $<?= number_format($product['bid_price'], 2) ?></p>
                        <div class="product-actions">
                            <form method="POST" action="cart.php" style="flex:1;">
                                <input type="hidden" name="cart_action" value="add">
                                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                                <input type="hidden" name="type" value="buy">
                                <input type="hidden" name="redirect" value="cart.php">
                                <button type="submit" class="buy-btn" style="width:100%;">Buy Now</button>
                            </form>
                            <form method="POST" action="cart.php" style="flex:1;">
                                <input type="hidden" name="cart_action" value="add">
                                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                                <input type="hidden" name="type" value="bid">
                                <input type="hidden" name="redirect" value="cart.php">
                                <button type="submit" class="bid-btn" style="width:100%;">Place Bid</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="shop-info">
            <h2>How Bidding Works</h2>
            <p>
                When you place a bid on an item, you're entering a competitive auction. Bidding typically runs for
                3–7 days depending on the item. You'll be notified if you're outbid, and the highest bidder wins
                when the auction closes. Don't want to wait? Use the "Buy Now" button to purchase immediately!
            </p>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; <?= date('Y') ?> 401 Thrift &mdash; Sustainable fashion, one find at a time.</p>
    </footer>

    <script>
        const filterBtns = document.querySelectorAll('.filter-btn');
        const cards = document.querySelectorAll('.product-card');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const filter = btn.dataset.filter;
                cards.forEach(card => {
                    card.style.display = filter === 'all' || card.dataset.category === filter ? '' : 'none';
                });
            });
        });
    </script>
</body>
</html>
