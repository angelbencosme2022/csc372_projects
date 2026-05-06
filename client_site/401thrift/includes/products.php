<?php
require_once __DIR__ . '/database-connection.php';
require_once __DIR__ . '/repository.php';

function thriftSeedProducts(): array {
    return [
        [
            'id' => 7,
            'name' => 'Elmo Lime Dad Cap',
            'description' => 'Bright green adjustable cap with embroidered Elmo detail and light wear.',
            'image' => 'images/elmo_hat.jpg',
            'image_alt' => 'Lime green Elmo baseball cap hanging on a store rack',
            'buy_price' => 24.00,
            'bid_price' => 16.00,
            'category' => 'accessories',
            'is_active' => 1,
        ],
        [
            'id' => 8,
            'name' => 'Gaming Quote Beanie',
            'description' => 'Black knit beanie with embroidered gaming quote and blue controller graphic.',
            'image' => 'images/gaming_hat.jpg',
            'image_alt' => 'Black beanie with gaming quote embroidery and blue controller icon',
            'buy_price' => 22.00,
            'bid_price' => 14.00,
            'category' => 'accessories',
            'is_active' => 1,
        ],
        [
            'id' => 9,
            'name' => 'Dragon Ball Graphic Hoodie',
            'description' => 'Gray pullover hoodie featuring a large Dragon Ball graphic with Goku and Frieza.',
            'image' => 'images/dragon_ball_hoodie.jpg',
            'image_alt' => 'Gray Dragon Ball graphic hoodie hanging on a rack',
            'buy_price' => 38.00,
            'bid_price' => 26.00,
            'category' => 'clothing',
            'is_active' => 1,
        ],
        [
            'id' => 10,
            'name' => 'Scenic Photo Graphic Tee',
            'description' => 'White vintage-style graphic tee with a large scenic printed photo on the front.',
            'image' => 'images/scenic_graphic_tee.jpg',
            'image_alt' => 'White scenic photo graphic t-shirt hanging on a rack',
            'buy_price' => 30.00,
            'bid_price' => 20.00,
            'category' => 'clothing',
            'is_active' => 1,
        ],
        [
            'id' => 11,
            'name' => 'American Made Red Tee',
            'description' => 'Bold red graphic t-shirt with large American Made lettering and flag detail.',
            'image' => 'images/american_made_tee_v2.jpg',
            'image_alt' => 'Red American Made graphic t-shirt laid flat',
            'buy_price' => 28.00,
            'bid_price' => 18.00,
            'category' => 'clothing',
            'is_active' => 1,
        ],
        [
            'id' => 12,
            'name' => 'Keurig K-Mini Mate',
            'description' => 'Compact single-serve Keurig coffee maker in box, sized for small kitchens and dorm setups.',
            'image' => 'images/keurig_mini_mate_v2.jpg',
            'image_alt' => 'Boxed Keurig K-Mini Mate coffee maker beside a window',
            'buy_price' => 48.00,
            'bid_price' => 34.00,
            'category' => 'home',
            'is_active' => 1,
        ],
        [
            'id' => 13,
            'name' => 'Vintage Storage Chest',
            'description' => 'Large wood-and-metal trunk with worn character, decorative hardware, and plenty of storage.',
            'image' => 'images/vintage_storage_chest_v2.jpg',
            'image_alt' => 'Vintage wood storage chest displayed on a furniture floor',
            'buy_price' => 95.00,
            'bid_price' => 70.00,
            'category' => 'furniture',
            'is_active' => 1,
        ],
        [
            'id' => 14,
            'name' => 'Masked Plush Dog',
            'description' => 'Soft plush dog toy with oversized eyes, floppy ears, and a playful black mask detail.',
            'image' => 'images/masked_plush_dog_v2.jpg',
            'image_alt' => 'Masked plush dog toy hanging on a store rack',
            'buy_price' => 18.00,
            'bid_price' => 12.00,
            'category' => 'toys',
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

    return ['all', 'clothing', 'accessories', 'home', 'furniture', 'toys'];
}
