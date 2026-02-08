

// ===== SELECTING HTML ELEMENTS =====
// Store DOM element references in variables to avoid unnecessary re-queries
const ctaMessage = document.getElementById('cta-message');
const exploreButton = document.getElementById('explore-btn');
const mainHeading = document.querySelector('h1');

// ===== UPDATING TEXT & MARKUP =====
// Update the main heading with dynamic content
mainHeading.innerHTML = 'Welcome to <span style="color: #f39c12;">401 Thrift</span>';

// Array of motivational messages to cycle through
const messages = [
    "Start exploring our collection today and discover your next favorite piece!",
    "New vintage finds added daily - don't miss out!",
    "Sustainable fashion starts here. Shop consciously!",
    "Unique pieces waiting for their next story. Will it be yours?"
];

let messageIndex = 0;

// ===== EVENT HANDLING =====
// Function to handle button click event
function handleExploreClick() {
    // Cycle through different call-to-action messages
    messageIndex = (messageIndex + 1) % messages.length;
    ctaMessage.textContent = messages[messageIndex];
    
    // Visual feedback - change button text temporarily
    const originalText = exploreButton.textContent;
    exploreButton.textContent = "Loading...";
    
    // Simulate loading and reset button text
    setTimeout(function() {
        exploreButton.textContent = originalText;
        
        // Update the CTA message styling for emphasis
        ctaMessage.style.color = '#f39c12';
        ctaMessage.style.fontStyle = 'italic';
        
        // Reset styling after 2 seconds
        setTimeout(function() {
            ctaMessage.style.color = '#2c3e50';
            ctaMessage.style.fontStyle = 'normal';
        }, 2000);
    }, 800);
}

// Assign the event listener to the button
exploreButton.addEventListener('click', handleExploreClick);

// ===== ADDITIONAL INTERACTIVITY =====
// Add hover effect to all paragraph elements
const paragraphs = document.querySelectorAll('.content p');

paragraphs.forEach(function(paragraph) {
    paragraph.addEventListener('mouseenter', function() {
        this.style.transition = 'all 0.3s ease';
        this.style.paddingLeft = '10px';
    });
    
    paragraph.addEventListener('mouseleave', function() {
        this.style.paddingLeft = '0';
    });
});

// Display welcome alert when page loads (optional - can be removed if not desired)
window.addEventListener('load', function() {
    console.log('401 Thrift website loaded successfully!');
    console.log('Interactive features are ready.');
});

// ===== PAGE INTERACTION COUNTER =====
// Track and display user interactions
let clickCount = 0;

exploreButton.addEventListener('click', function() {
    clickCount++;
    console.log(`Explore button clicked ${clickCount} time(s)`);
    
    // Easter egg: special message after 5 clicks
    if (clickCount === 5) {
        ctaMessage.textContent = "Wow, you really love exploring! 🎉 Check back tomorrow for exclusive deals!";
        ctaMessage.style.color = '#e74c3c';
        ctaMessage.style.fontWeight = '700';
    }
});