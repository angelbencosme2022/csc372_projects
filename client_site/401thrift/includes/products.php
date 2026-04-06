<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/repository.php';

function thriftSeedProducts(): array {
    return [
        [
            'id' => 1,
            'name' => 'Vintage Denim Jacket',
            'description' => 'Classic 90s denim jacket with perfect distressing. Size: Medium',
            'image' => 'images/product1.jpg',
            'image_alt' => 'Vintage denim jacket with distressed finish',
            'buy_price' => 45.00,
            'bid_price' => 32.00,
            'category' => 'clothing',
            'is_active' => 1,
        ],
        [
            'id' => 2,
            'name' => 'Leather Messenger Bag',
            'description' => 'Genuine leather bag with adjustable strap. Great condition.',
            'image' => 'images/product2.jpg',
            'image_alt' => 'Leather messenger bag in brown',
            'buy_price' => 60.00,
            'bid_price' => 45.00,
            'category' => 'accessories',
            'is_active' => 1,
        ],
        [
            'id' => 3,
            'name' => 'Vintage Nike Sneakers',
            'description' => 'Retro Nike kicks from the 80s. Size: 10. Minimal wear.',
            'image' => 'images/product3.jpg',
            'image_alt' => 'Vintage Nike sneakers in white and red',
            'buy_price' => 85.00,
            'bid_price' => 65.00,
            'category' => 'shoes',
            'is_active' => 1,
        ],
        [
            'id' => 4,
            'name' => 'Vintage Band Tee',
            'description' => 'Original concert tee from the 90s. Size: Large. Authentic vintage.',
            'image' => 'images/product4.jpg',
            'image_alt' => 'Vintage band t-shirt',
            'buy_price' => 38.00,
            'bid_price' => 28.00,
            'category' => 'clothing',
            'is_active' => 1,
        ],
        [
            'id' => 5,
            'name' => 'Retro Sunglasses',
            'description' => 'Classic aviator style with gold frames. Perfect condition.',
            'image' => 'images/product5.jpg',
            'image_alt' => 'Vintage sunglasses with gold frames',
            'buy_price' => 25.00,
            'bid_price' => 18.00,
            'category' => 'accessories',
            'is_active' => 1,
        ],
        [
            'id' => 6,
            'name' => 'Flannel Shirt',
            'description' => 'Cozy plaid flannel. Size: Medium. Perfect for layering.',
            'image' => 'images/product6.jpg',
            'image_alt' => 'Vintage flannel shirt in red plaid',
            'buy_price' => 32.00,
            'bid_price' => 22.00,
            'category' => 'clothing',
            'is_active' => 1,
        ],
    ];
}

function getProducts(): array {
    if (thriftDbConfigured()) {
        try {
            return fetchAllProducts();
        } catch (Throwable $e) {
            return thriftSeedProducts();
        }
    }

    return thriftSeedProducts();
}

function getProductById(int $id): ?array {
    if (thriftDbConfigured()) {
        try {
            $product = fetchProductById($id);
            if ($product) {
                return $product;
            }
        } catch (Throwable $e) {
            // Fall back to seed data.
        }
    }

    foreach (thriftSeedProducts() as $product) {
        if ($product['id'] === $id) {
            return $product;
        }
    }

    return null;
}

function getCategories(): array {
    if (thriftDbConfigured()) {
        try {
            $categories = fetchProductCategories();
            if (!empty($categories)) {
                return array_merge(['all'], $categories);
            }
        } catch (Throwable $e) {
            // Fall back to seed data.
        }
    }

    return ['all', 'clothing', 'accessories', 'shoes'];
}
