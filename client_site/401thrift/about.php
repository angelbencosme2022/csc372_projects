<?php
$activePage = 'about';
require_once 'includes/cart.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us — 401 Thrift</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <div class="content">
        <h1>About 401 Thrift</h1>

        <h2>Our Mission</h2>
        <img src="images/about-mission.jpg" alt="Curated vintage clothing collection"
             class="content-image" onerror="this.style.display='none'">
        <p>
            At 401 Thrift, we believe that fashion should be sustainable, affordable, and unique. Our mission is
            to provide access to high-quality vintage and secondhand clothing while reducing fashion waste and
            promoting conscious consumption. Every item in our collection has a story, and we're passionate about
            helping these pieces find their next chapter.
        </p>
        <p>
            We carefully curate each item, ensuring that only the best vintage finds make it to our shop. From
            retro band tees to classic denim jackets, vintage sneakers to timeless accessories, we hunt for pieces
            that combine style, quality, and character. Our goal is simple: make sustainable fashion accessible
            and exciting for everyone.
        </p>

        <h2>Our Story</h2>
        <p>
            401 Thrift started as a passion project born from countless hours spent exploring thrift stores, vintage
            shops, and estate sales. What began as a personal hobby quickly turned into something bigger when friends
            and family kept asking where to find unique pieces like the ones we were discovering.
        </p>
        <img src="images/about-story.jpg" alt="Vintage thrift store finds"
             class="content-image" onerror="this.style.display='none'">
        <p>
            We realized there was a gap in the market: people wanted access to curated vintage finds without spending
            hours digging through crowded thrift stores. They wanted the thrill of discovery with the convenience of
            online shopping. That's when 401 Thrift was born. We do the hunting so you don't have to, bringing the
            best thrift finds directly to you.
        </p>
        <p>
            Today, we're proud to serve a growing community of vintage enthusiasts, sustainable fashion advocates,
            and anyone looking for unique pieces that stand out from fast fashion. Every item we post has been
            hand-selected with care, and we love seeing our finds get a second life with amazing people like you.
        </p>

        <h2>Why Shop Vintage?</h2>
        <p>
            Shopping vintage and secondhand isn't just about style — it's about making a positive impact. The fashion
            industry is one of the largest polluters globally, and fast fashion contributes to massive waste. By
            choosing vintage, you're reducing demand for new production, keeping quality items out of landfills,
            and celebrating craftsmanship that stands the test of time.
        </p>
        <p>
            Plus, vintage fashion offers something that new clothing simply can't: uniqueness. When you wear vintage,
            you're wearing pieces that aren't mass-produced. You're less likely to run into someone wearing the same
            thing, and you're expressing a style that's truly your own.
        </p>

        <h2>Our Values</h2>
        <div class="values-list">
            <p><strong>Sustainability:</strong> We're committed to reducing fashion waste by giving quality items a second life and promoting circular fashion.</p>
            <p><strong>Quality:</strong> Every item is inspected and selected for its condition, style, and craftsmanship. We only sell what we'd wear ourselves.</p>
            <p><strong>Accessibility:</strong> With our bidding system and competitive pricing, we make vintage fashion accessible to everyone, regardless of budget.</p>
            <p><strong>Transparency:</strong> We provide detailed descriptions, honest condition assessments, and clear photos so you know exactly what you're getting.</p>
            <p><strong>Community:</strong> We're building a community of people who appreciate sustainable fashion, unique style, and the stories behind vintage pieces.</p>
        </div>

        <div class="about-cta">
            <p>Thank you for supporting 401 Thrift and choosing sustainable fashion. We're excited to help you discover your next favorite piece!</p>
            <a href="shop.php" class="cta-button">Start Shopping</a>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; <?= date('Y') ?> 401 Thrift &mdash; Sustainable fashion, one find at a time.</p>
    </footer>

</body>
</html>
