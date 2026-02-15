/* 
   Name: [Your Name]
   Date: February 7, 2026
   Description: JavaScript for 401 Thrift Shop page - handles product filtering and interactions
*/

// ===== SELECTING HTML ELEMENTS =====
const filterButtons = document.querySelectorAll('.filter-btn');
const productCards = document.querySelectorAll('.product-card');
const buyButtons = document.querySelectorAll('.buy-btn');
const bidButtons = document.querySelectorAll('.bid-btn');

// ===== FILTER FUNCTIONALITY =====
// Function to filter products by category
function filterProducts(category) {
    productCards.forEach(function(card) {
        const cardCategory = card.getAttribute('data-category');
        
        if (category === 'all' || cardCategory === category) {
            card.style.display = 'block';
            // Add fade-in animation
            card.style.animation = 'fadeIn 0.5s ease';
        } else {
            card.style.display = 'none';
        }
    });
}

// Add event listeners to filter buttons
filterButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        // Remove active class from all buttons
        filterButtons.forEach(btn => btn.classList.remove('active'));
        
        // Add active class to clicked button
        this.classList.add('active');
        
        // Get category from data attribute
        const category = this.getAttribute('data-category');
        
        // Filter products
        filterProducts(category);
        
        console.log(`Filtering products: ${category}`);
    });
});

// ===== BUY NOW FUNCTIONALITY =====
// Handle "Buy Now" button clicks
buyButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        const productCard = this.closest('.product-card');
        const productName = productCard.querySelector('h3').textContent;
        const productPrice = productCard.querySelector('.product-price').textContent;
        
        // Visual feedback
        this.textContent = 'Added to Cart!';
        this.style.backgroundColor = '#229954';
        
        // Alert user
        alert(`${productName} has been added to your cart!\n${productPrice}`);
        
        // Reset button after 2 seconds
        setTimeout(() => {
            this.textContent = 'Buy Now';
            this.style.backgroundColor = '#27ae60';
        }, 2000);
        
        console.log(`Purchase initiated: ${productName}`);
    });
});

// ===== BIDDING FUNCTIONALITY =====
// Handle "Place Bid" button clicks
bidButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        const productCard = this.closest('.product-card');
        const productName = productCard.querySelector('h3').textContent;
        const currentBid = productCard.querySelector('.product-bid').textContent;
        
        // Prompt user for bid amount
        const bidAmount = prompt(`Enter your bid for ${productName}\n${currentBid}`);
        
        if (bidAmount && !isNaN(bidAmount) && parseFloat(bidAmount) > 0) {
            // Visual feedback
            this.textContent = 'Bid Placed!';
            this.style.backgroundColor = '#2980b9';
            
            // Update current bid display
            productCard.querySelector('.product-bid').textContent = `Current Bid: $${parseFloat(bidAmount).toFixed(2)}`;
            
            alert(`Your bid of $${parseFloat(bidAmount).toFixed(2)} has been placed!\nYou'll be notified if you're outbid.`);
            
            // Reset button after 2 seconds
            setTimeout(() => {
                this.textContent = 'Place Bid';
                this.style.backgroundColor = '#3498db';
            }, 2000);
            
            console.log(`Bid placed: $${bidAmount} for ${productName}`);
        } else if (bidAmount !== null) {
            alert('Please enter a valid bid amount.');
        }
    });
});

// ===== PRODUCT CARD HOVER EFFECTS =====
productCards.forEach(function(card) {
    card.addEventListener('mouseenter', function() {
        this.style.transition = 'all 0.3s ease';
    });
});

// ===== PAGE LOAD ANALYTICS =====
window.addEventListener('load', function() {
    console.log('Shop page loaded successfully!');
    console.log(`Total products: ${productCards.length}`);
    console.log('Filter and purchase features are ready.');
});

// Add fadeIn animation keyframes dynamically
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
`;
document.head.appendChild(style);