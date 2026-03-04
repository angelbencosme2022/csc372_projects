// ===== LOAD MODULES =====
const express    = require('express');
const { engine } = require('express-handlebars');
const path       = require('path');

// ===== CREATE APP =====
const app = express();

// ===== CONFIGURATION =====
const PORT = 3000;

// ===== CONFIGURE HANDLEBARS =====
app.engine('handlebars', engine({
    defaultLayout: 'main',
    layoutsDir: path.join(__dirname, 'views/layouts'),
}));
app.set('view engine', 'handlebars');
app.set('views', path.join(__dirname, 'views'));

// ===== SERVE STATIC FILES =====
app.use(express.static(path.join(__dirname, 'public')));

// ===== ROUTES =====

// --- Home ---
app.get(['/', '/index'], (req, res) => {
    res.render('index', {
        pageTitle:   '401 Thrift - Unique Finds, Unbeatable Prices',
        activeHome:  true,
        scriptHome:  true,
        ctaMessage:  'Start exploring our collection today and discover your next favorite piece!',
        itemCount:   42,
        lastUpdated: new Date().toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }),
    });
});

// --- Shop ---
app.get('/shop', (req, res) => {
    res.render('shop', {
        pageTitle: 'Shop',
        activeShop: true,
        scriptShop: true,
        products: [
            { category: 'clothing',    image: 'product1.jpg', imageAlt: 'Vintage denim jacket', name: 'Vintage Denim Jacket',   description: 'Classic 90s denim jacket with perfect distressing. Size: Medium', buyPrice: '45.00', currentBid: '32.00' },
            { category: 'accessories', image: 'product2.jpg', imageAlt: 'Leather messenger bag', name: 'Leather Messenger Bag',  description: 'Genuine leather bag with adjustable strap. Great condition.',       buyPrice: '60.00', currentBid: '45.00' },
            { category: 'shoes',       image: 'product3.jpg', imageAlt: 'Vintage Nike sneakers', name: 'Vintage Nike Sneakers',  description: 'Retro Nike kicks from the 80s. Size: 10. Minimal wear.',           buyPrice: '85.00', currentBid: '65.00' },
            { category: 'clothing',    image: 'product4.jpg', imageAlt: 'Vintage band t-shirt',  name: 'Vintage Band Tee',       description: 'Original concert tee from the 90s. Size: Large. Authentic vintage.', buyPrice: '38.00', currentBid: '28.00' },
            { category: 'accessories', image: 'product5.jpg', imageAlt: 'Retro sunglasses',      name: 'Retro Sunglasses',       description: 'Classic aviator style with gold frames. Perfect condition.',        buyPrice: '25.00', currentBid: '18.00' },
            { category: 'clothing',    image: 'product6.jpg', imageAlt: 'Vintage flannel shirt', name: 'Flannel Shirt',          description: 'Cozy plaid flannel. Size: Medium. Perfect for layering.',          buyPrice: '32.00', currentBid: '22.00' },
        ],
    });
});

// --- About ---
app.get('/about', (req, res) => {
    res.render('about', {
        pageTitle:   'About Us',
        activeAbout: true,
        scriptAbout: true,
        teamSize:    3,
        location:    'Rhode Island',
        values: [
            {
                title:       'Sustainability',
                description: 'We\'re committed to reducing fashion waste by giving quality items a second life and promoting circular fashion.',
            },
            {
                title:       'Quality',
                description: 'Every item is inspected and selected for its condition, style, and craftsmanship. We only sell what we\'d wear ourselves.',
            },
            {
                title:       'Accessibility',
                description: 'With our bidding system and competitive pricing, we make vintage fashion accessible to everyone, regardless of budget.',
            },
            {
                title:       'Transparency',
                description: 'We provide detailed descriptions, honest condition assessments, and clear photos so you know exactly what you\'re getting.',
            },
            {
                title:       'Community',
                description: 'We\'re building a community of people who appreciate sustainable fashion, unique style, and the stories behind vintage pieces.',
            },
        ],
    });
});

// --- Contact ---
app.get('/contact', (req, res) => {
    res.render('contact', {
        pageTitle:      'Contact Us',
        activeContact:  true,
        scriptContact:  true,
        contactEmail:   'hello@401thrift.com',
        instagramHandle:'@401thrift',
        businessHours:  'Monday - Friday: 9am - 6pm EST',
        faqs: [
            {
                question: 'How long does shipping take?',
                answer:   'We ship within 1-2 business days of purchase. Delivery typically takes 3-5 business days depending on your location. You\'ll receive tracking information once your order ships.',
            },
            {
                question: 'What if an item doesn\'t fit?',
                answer:   'We accept returns within 7 days of delivery. Items must be unworn and in the same condition you received them. Contact us to initiate a return, and we\'ll provide a prepaid shipping label.',
            },
            {
                question: 'How does bidding work?',
                answer:   'When you place a bid, you\'re entering an auction for that item. Auctions typically run 3-7 days. If someone outbids you, you\'ll receive a notification. The highest bidder when time runs out wins the item. Don\'t want to bid? Use the "Buy Now" option for instant purchase.',
            },
            {
                question: 'Do you buy vintage items?',
                answer:   'Yes! We\'re always looking for quality vintage pieces. If you have items you\'d like to sell, send us photos and descriptions via email or use the contact form above. We\'ll review and get back to you with an offer.',
            },
            {
                question: 'Are the items authentic?',
                answer:   'Absolutely. We carefully authenticate all branded items and provide detailed condition reports. If we can\'t verify authenticity, we don\'t list it. Your trust is important to us.',
            },
        ],
    });
});

// --- Checkout ---
app.get('/checkout', (req, res) => {
    res.render('checkout', {
        pageTitle:      'Checkout',
        stripeJS:       true,
        scriptCheckout: true,
        shippingCost:   '5.99',
    });
});

// ===== 404 CATCH-ALL =====
app.use((req, res) => {
    res.status(404).render('404', {
        pageTitle: '404 - Page Not Found',
        layout:    'main',
    });
});

// ===== 500 ERROR HANDLER =====
app.use((err, req, res, next) => {
    console.error('Server error:', err.stack);
    res.status(500).render('500', {
        pageTitle: '500 - Server Error',
        layout:    'main',
    });
});

// ===== START SERVER =====
app.listen(PORT, () => {
    console.log(`401 Thrift server is running!`);
    console.log(`Visit: http://localhost:${PORT}`);
});