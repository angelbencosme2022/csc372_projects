<?php
// $activePage should be set before including this file
// e.g., $activePage = 'home';
if (!isset($activePage)) $activePage = '';

// cart.php must already be included by the parent page
$cartCount = function_exists('cartCount') ? cartCount() : 0;
?>
<div class="header">
    <img src="images/logo.png" alt="401 Thrift Logo - Vintage clothing and unique finds" class="logo">
    <nav>
        <ul class="nav-menu">
            <li><a href="index.php"   <?= $activePage === 'home'    ? 'class="active"' : '' ?>>Home</a></li>
            <li><a href="shop.php"    <?= $activePage === 'shop'    ? 'class="active"' : '' ?>>Shop</a></li>
            <li><a href="about.php"   <?= $activePage === 'about'   ? 'class="active"' : '' ?>>About</a></li>
            <li><a href="contact.php" <?= $activePage === 'contact' ? 'class="active"' : '' ?>>Contact</a></li>
            <li>
                <a href="cart.php" <?= $activePage === 'cart' ? 'class="active"' : '' ?>>
                    🛒 Cart<?= $cartCount > 0 ? ' <span class="cart-badge">' . $cartCount . '</span>' : '' ?>
                </a>
            </li>
        </ul>
    </nav>
</div>