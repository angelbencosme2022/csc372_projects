<?php
/**
 * Shared header / nav.
 * Expects $activePage to be set by the including page.
 * Expects cart.php to have already been included (for cartCount()).
 */
if (!isset($activePage)) $activePage = '';
$cartCount = function_exists('cartCount') ? cartCount() : 0;
?>
<header class="header">
    <!-- Show text logo as fallback if logo.png is missing -->
    <a href="index.php" style="text-decoration:none;">
        <img src="images/logo.png"
             alt="401 Thrift"
             class="logo"
             onerror="this.style.display='none';this.nextElementSibling.style.display='inline-block';">
        <span class="logo-text" style="display:none;">401 Thrift</span>
    </a>
    <nav>
        <ul class="nav-menu">
            <li><a href="index.php"   <?= $activePage === 'home'    ? 'class="active"' : '' ?>>Home</a></li>
            <li><a href="shop.php"    <?= $activePage === 'shop'    ? 'class="active"' : '' ?>>Shop</a></li>
            <li><a href="about.php"   <?= $activePage === 'about'   ? 'class="active"' : '' ?>>About</a></li>
            <li><a href="contact.php" <?= $activePage === 'contact' ? 'class="active"' : '' ?>>Contact</a></li>
            <li><a href="admin-products.php" <?= $activePage === 'inventory' ? 'class="active"' : '' ?>>Inventory</a></li>
            <li>
                <a href="cart.php" <?= $activePage === 'cart' ? 'class="active"' : '' ?>>
                    🛒 Cart<?= $cartCount > 0 ? ' <span class="cart-badge">' . $cartCount . '</span>' : '' ?>
                </a>
            </li>
        </ul>
    </nav>
</header>
