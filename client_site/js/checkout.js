
// ===== STRIPE CONFIGURATION =====
// Get the API key from the config file
const STRIPE_PUBLISHABLE_KEY = window.CONFIG.STRIPE_PUBLISHABLE_KEY;

// Check if the key is properly configured
if (!STRIPE_PUBLISHABLE_KEY || STRIPE_PUBLISHABLE_KEY === 'pk_test_YOUR_PUBLISHABLE_KEY_HERE') {
    console.error('⚠️ Stripe API key not configured!');
    console.error('Please follow these steps:');
    console.error('1. Copy js/config.example.js to js/config.js');
    console.error('2. Add your Stripe publishable key to js/config.js');
    console.error('3. Make sure js/config.js is in your .gitignore');
}

// Initialize Stripe
const stripe = Stripe(STRIPE_PUBLISHABLE_KEY);

// Create Stripe Elements instance
const elements = stripe.elements();

// Custom styling for card element
const style = {
    base: {
        color: '#32325d',
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
            color: '#aab7c4'
        }
    },
    invalid: {
        color: '#fa755a',
        iconColor: '#fa755a'
    }
};

// Create card element
const cardElement = elements.create('card', { style: style });
cardElement.mount('#card-element');

// ===== SELECTING HTML ELEMENTS =====
const paymentForm = document.getElementById('payment-form');
const submitButton = document.getElementById('submit-payment');
const buttonText = document.getElementById('button-text');
const spinner = document.getElementById('spinner');
const paymentMessage = document.getElementById('payment-message');
const cardErrors = document.getElementById('card-errors');
const cartItemsContainer = document.getElementById('cart-items');
const subtotalElement = document.getElementById('subtotal');
const shippingElement = document.getElementById('shipping');
const totalElement = document.getElementById('total');

// ===== CART DATA =====
// In a real app, this would come from localStorage or a backend
let cart = [
    {
        id: 1,
        name: 'Vintage Denim Jacket',
        price: 45.00,
        quantity: 1,
        image: 'images/product1.jpg'
    },
    {
        id: 2,
        name: 'Leather Messenger Bag',
        price: 60.00,
        quantity: 1,
        image: 'images/product2.jpg'
    }
];

// Shipping cost
const SHIPPING_COST = 5.99;

// ===== DISPLAY CART ITEMS =====
function displayCartItems() {
    cartItemsContainer.innerHTML = '';
    let subtotal = 0;

    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;

        const cartItem = document.createElement('div');
        cartItem.className = 'cart-item';
        cartItem.innerHTML = `
            <img src="${item.image}" alt="${item.name}" class="cart-item-image">
            <div class="cart-item-details">
                <h4>${item.name}</h4>
                <p>Quantity: ${item.quantity}</p>
                <p class="cart-item-price">$${itemTotal.toFixed(2)}</p>
            </div>
        `;
        cartItemsContainer.appendChild(cartItem);
    });

    // Update totals
    subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
    shippingElement.textContent = `$${SHIPPING_COST.toFixed(2)}`;
    const total = subtotal + SHIPPING_COST;
    totalElement.textContent = `$${total.toFixed(2)}`;

    return total;
}

// ===== CARD ELEMENT ERROR HANDLING =====
cardElement.on('change', function(event) {
    if (event.error) {
        cardErrors.textContent = event.error.message;
        cardErrors.style.color = '#fa755a';
    } else {
        cardErrors.textContent = '';
    }
});

// ===== FORM VALIDATION =====
function validateForm() {
    const name = document.getElementById('customer-name').value.trim();
    const email = document.getElementById('customer-email').value.trim();
    const address = document.getElementById('address-line1').value.trim();
    const city = document.getElementById('city').value.trim();
    const state = document.getElementById('state').value.trim();
    const zip = document.getElementById('zip').value.trim();

    if (!name || !email || !address || !city || !state || !zip) {
        showMessage('Please fill in all required fields.', 'error');
        return false;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showMessage('Please enter a valid email address.', 'error');
        return false;
    }

    return true;
}

// ===== PAYMENT FORM SUBMISSION =====
paymentForm.addEventListener('submit', async function(event) {
    event.preventDefault();

    // Validate form
    if (!validateForm()) {
        return;
    }

    // Disable submit button and show loading
    setLoading(true);

    // Get customer information
    const customerName = document.getElementById('customer-name').value;
    const customerEmail = document.getElementById('customer-email').value;

    try {
        // Create payment method with Stripe
        const { paymentMethod, error } = await stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
            billing_details: {
                name: customerName,
                email: customerEmail,
                address: {
                    line1: document.getElementById('address-line1').value,
                    line2: document.getElementById('address-line2').value,
                    city: document.getElementById('city').value,
                    state: document.getElementById('state').value,
                    postal_code: document.getElementById('zip').value,
                    country: 'US'
                }
            }
        });

        if (error) {
            // Show error to customer
            showMessage(error.message, 'error');
            setLoading(false);
            return;
        }

        // Payment method created successfully
        console.log('Payment Method created:', paymentMethod.id);

        // In a real application, you would send this to your backend server
        // to create a PaymentIntent and process the payment
        // For now, we'll simulate a successful payment
        await simulatePaymentProcessing(paymentMethod);

    } catch (error) {
        console.error('Payment error:', error);
        showMessage('An unexpected error occurred. Please try again.', 'error');
        setLoading(false);
    }
});

// ===== SIMULATE PAYMENT PROCESSING =====
// In production, this would be handled by your backend server
async function simulatePaymentProcessing(paymentMethod) {
    // Simulate API call to backend
    setTimeout(() => {
        // Simulate successful payment
        const success = true; // In real app, this comes from your backend

        if (success) {
            showMessage('Payment successful! Thank you for your purchase.', 'success');
            
            // Clear cart and redirect after 3 seconds
            setTimeout(() => {
                alert('Order confirmed! You will receive a confirmation email shortly.');
                window.location.href = 'index.html';
            }, 3000);
        } else {
            showMessage('Payment failed. Please try again.', 'error');
            setLoading(false);
        }
    }, 2000);
}

// ===== HELPER FUNCTIONS =====
function setLoading(isLoading) {
    if (isLoading) {
        submitButton.disabled = true;
        spinner.classList.remove('hidden');
        buttonText.textContent = 'Processing...';
    } else {
        submitButton.disabled = false;
        spinner.classList.add('hidden');
        buttonText.textContent = 'Pay Now';
    }
}

function showMessage(message, type) {
    paymentMessage.textContent = message;
    paymentMessage.className = `payment-message ${type}`;
    paymentMessage.style.display = 'block';

    // Hide message after 5 seconds
    setTimeout(() => {
        paymentMessage.style.display = 'none';
    }, 5000);
}

// ===== PAGE LOAD =====
window.addEventListener('load', function() {
    console.log('Checkout page loaded successfully!');
    console.log('Stripe integration ready.');
    
    // Display cart items
    displayCartItems();
    
    // Focus on name field
    document.getElementById('customer-name').focus();
});

// ===== DEMO: Add items to cart from shop page =====
// This function can be called from shop.js to add items
window.addToCart = function(item) {
    const existingItem = cart.find(i => i.id === item.id);
    
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({
            ...item,
            quantity: 1
        });
    }
    
    // Store in localStorage for persistence
    localStorage.setItem('cart', JSON.stringify(cart));
    
    console.log('Item added to cart:', item);
    displayCartItems();
};

// Load cart from localStorage if available
const savedCart = localStorage.getItem('cart');
if (savedCart) {
    cart = JSON.parse(savedCart);
    displayCartItems();
}