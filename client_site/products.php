<?php
/**
 * Product data for 401 Thrift
 * To connect to a database later, replace this array with a DB query
 * and return the same structure.
 */

function getProducts() {
    return [
        [
            'id'          => 1,
            'name'        => 'Vintage Denim Jacket',
            'description' => 'Classic 90s denim jacket with perfect distressing. Size: Medium',
            'image'       => 'images/product1.jpg',
            'image_alt'   => 'Vintage denim jacket with distressed finish',
            'buy_price'   => 45.00,
            'bid_price'   => 32.00,
            'category'    => 'clothing',
        ],
        [
            'id'          => 2,
            'name'        => 'Leather Messenger Bag',
            'description' => 'Genuine leather bag with adjustable strap. Great condition.',
            'image'       => 'images/product2.jpg',
            'image_alt'   => 'Leather messenger bag in brown',
            'buy_price'   => 60.00,
            'bid_price'   => 45.00,
            'category'    => 'accessories',
        ],
        [
            'id'          => 3,
            'name'        => 'Vintage Nike Sneakers',
            'description' => 'Retro Nike kicks from the 80s. Size: 10. Minimal wear.',
            'image'       => 'images/product3.jpg',
            'image_alt'   => 'Vintage Nike sneakers in white and red',
            'buy_price'   => 85.00,
            'bid_price'   => 65.00,
            'category'    => 'shoes',
        ],
        [
            'id'          => 4,
            'name'        => 'Vintage Band Tee',
            'description' => "Original concert tee from the 90s. Size: Large. Authentic vintage.",
            'image'       => 'images/product4.jpg',
            'image_alt'   => 'Vintage band t-shirt',
            'buy_price'   => 38.00,
            'bid_price'   => 28.00,
            'category'    => 'clothing',
        ],
        [
            'id'          => 5,
            'name'        => 'Retro Sunglasses',
            'description' => 'Classic aviator style with gold frames. Perfect condition.',
            'image'       => 'images/product5.jpg',
            'image_alt'   => 'Vintage sunglasses with gold frames',
            'buy_price'   => 25.00,
            'bid_price'   => 18.00,
            'category'    => 'accessories',
        ],
        [
            'id'          => 6,
            'name'        => 'Flannel Shirt',
            'description' => 'Cozy plaid flannel. Size: Medium. Perfect for layering.',
            'image'       => 'images/product6.jpg',
            'image_alt'   => 'Vintage flannel shirt in red plaid',
            'buy_price'   => 32.00,
            'bid_price'   => 22.00,
            'category'    => 'clothing',
        ],
    ];
}

function getCategories() {
    return ['all', 'clothing', 'accessories', 'shoes'];
}