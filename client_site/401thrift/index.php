<?php
$activePage = 'home';
require_once 'includes/cart.php';   // starts session, handles POST
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 Thrift — Unique Finds, Unbeatable Prices</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <!-- Hero -->
    <div class="hero">
        <h1>Welcome to 401 Thrift</h1>
        <p>
            One-of-a-kind vintage clothing, accessories, and secondhand finds —
            hand-picked, quality-checked, and brought straight to you.
        </p>
        <button id="explore-btn" class="cta-button">Browse Latest Finds</button>
    </div>

    <div class="content">

        <p>
            401 Thrift is your destination for one-of-a-kind vintage clothing, accessories, and unique secondhand finds.
            We curate the best thrift discoveries and bring them directly to you. Whether you're hunting for vintage
            streetwear, retro accessories, or sustainable fashion alternatives, our collection features hand-picked items
            that combine quality, style, and affordability.
        </p>

        <img src="images/dragon_ball_hoodie.jpg"
             alt="Dragon Ball graphic hoodie from the current collection"
             class="content-image"
             onerror="this.style.display='none'">

        <p>
            Every item on 401 Thrift has been carefully selected and inspected for quality. Browse our rotating inventory,
            place bids on your favorite pieces, or purchase items outright. New finds are added regularly, so check back
            often to discover something special before it's gone!
        </p>

        <h2>How It Works</h2>
        <p>
            Shopping at 401 Thrift is simple and exciting. Browse our current collection of thrifted treasures, each
            photographed and described in detail. Found something you love? You have two options: purchase it immediately
            at the listed price, or place a bid if you're looking for a deal. Our bidding system runs for a set period,
            giving everyone a fair chance to own unique pieces at competitive prices.
        </p>

        <img src="images/scenic_graphic_tee.jpg"
             alt="Scenic photo graphic tee available in the shop"
             class="content-image"
             onerror="this.style.display='none'">

        <h2>Why Choose 401 Thrift?</h2>
        <p>
            At 401 Thrift, sustainability meets style. By choosing secondhand, you're not only finding unique pieces that
            stand out from mass-produced fashion, but you're also making an environmentally conscious choice. Each purchase
            supports sustainable shopping practices and gives quality items a second life. Plus, our transparent pricing
            and bidding system ensures you always get the best value for authentic vintage and thrift finds.
        </p>

        <p id="cta-message">Start exploring our collection today and discover your next favorite piece!</p>
        <button id="shop-btn" class="cta-button">Shop Now</button>

    </div>

    <footer class="footer">
        <p>&copy; <?= date('Y') ?> 401 Thrift &mdash; Sustainable fashion, one find at a time.</p>
    </footer>

    <script>
        // Explore / Shop buttons navigate to shop
        document.getElementById('explore-btn').addEventListener('click', () => {
            window.location.href = 'shop.php';
        });
        document.getElementById('shop-btn').addEventListener('click', () => {
            window.location.href = 'shop.php';
        });
    </script>
</body>
</html>
