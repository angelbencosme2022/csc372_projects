CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(120) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    image_alt VARCHAR(255) NOT NULL,
    buy_price DECIMAL(10,2) NOT NULL,
    bid_price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS contact_messages (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL,
    subject VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS orders (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_number VARCHAR(30) NOT NULL,
    customer_name VARCHAR(120) NOT NULL,
    customer_email VARCHAR(190) NOT NULL,
    address_line1 VARCHAR(150) NOT NULL,
    address_line2 VARCHAR(150) DEFAULT '',
    city VARCHAR(100) NOT NULL,
    state VARCHAR(50) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    shipping DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    payment_status VARCHAR(50) NOT NULL,
    payment_reference VARCHAR(120) DEFAULT '',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uniq_order_number (order_number)
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    product_name VARCHAR(120) NOT NULL,
    purchase_type VARCHAR(20) NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    line_total DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_order_items_order
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

INSERT INTO products (name, description, image, image_alt, buy_price, bid_price, category, is_active) VALUES
('Vintage Denim Jacket', 'Classic 90s denim jacket with perfect distressing. Size: Medium', 'images/product1.jpg', 'Vintage denim jacket with distressed finish', 45.00, 32.00, 'clothing', 1),
('Leather Messenger Bag', 'Genuine leather bag with adjustable strap. Great condition.', 'images/product2.jpg', 'Leather messenger bag in brown', 60.00, 45.00, 'accessories', 1),
('Vintage Nike Sneakers', 'Retro Nike kicks from the 80s. Size: 10. Minimal wear.', 'images/product3.jpg', 'Vintage Nike sneakers in white and red', 85.00, 65.00, 'shoes', 1),
('Vintage Band Tee', 'Original concert tee from the 90s. Size: Large. Authentic vintage.', 'images/product4.jpg', 'Vintage band t-shirt', 38.00, 28.00, 'clothing', 1),
('Retro Sunglasses', 'Classic aviator style with gold frames. Perfect condition.', 'images/product5.jpg', 'Vintage sunglasses with gold frames', 25.00, 18.00, 'accessories', 1),
('Flannel Shirt', 'Cozy plaid flannel. Size: Medium. Perfect for layering.', 'images/product6.jpg', 'Vintage flannel shirt in red plaid', 32.00, 22.00, 'clothing', 1);
