/* 
   Name: [Your Name]
   Date: February 7, 2026
   Description: JavaScript for 401 Thrift About page - handles interactive elements
*/

// ===== SELECTING HTML ELEMENTS =====
const shopButton = document.getElementById('shop-cta');
const valuesList = document.querySelectorAll('.values-list p');
const faqItems = document.querySelectorAll('.faq-item');

// ===== SHOP BUTTON FUNCTIONALITY =====
// Redirect to shop page when button is clicked
shopButton.addEventListener('click', function() {
    // Visual feedback
    this.textContent = 'Taking you to the shop...';
    this.style.backgroundColor = '#e67e22';
    
    // Redirect after short delay
    setTimeout(function() {
        window.location.href = 'shop.html';
    }, 800);
    
    console.log('Redirecting to shop page...');
});

// ===== VALUES SECTION ANIMATION =====
// Add hover effect to values items
valuesList.forEach(function(value, index) {
    // Stagger the initial fade-in
    value.style.opacity = '0';
    value.style.transform = 'translateX(-20px)';
    value.style.transition = 'all 0.5s ease';
    
    setTimeout(function() {
        value.style.opacity = '1';
        value.style.transform = 'translateX(0)';
    }, 200 * index);
    
    // Add hover effect
    value.addEventListener('mouseenter', function() {
        this.style.paddingLeft = '30px';
        this.style.backgroundColor = '#f9f9f9';
        this.style.borderRadius = '5px';
    });
    
    value.addEventListener('mouseleave', function() {
        this.style.paddingLeft = '20px';
        this.style.backgroundColor = 'transparent';
    });
});

// ===== IMAGE HOVER EFFECTS =====
const images = document.querySelectorAll('.content-image');

images.forEach(function(img) {
    img.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.05)';
        this.style.transition = 'transform 0.3s ease';
    });
    
    img.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
    });
});

// ===== SCROLL REVEAL EFFECT =====
// Show elements as user scrolls down the page
function revealOnScroll() {
    const reveals = document.querySelectorAll('.values-list p, .about-cta');
    
    reveals.forEach(function(element) {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;
        
        if (elementTop < window.innerHeight - elementVisible) {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }
    });
}

window.addEventListener('scroll', revealOnScroll);

// ===== PAGE LOAD ACTIONS =====
window.addEventListener('load', function() {
    console.log('About page loaded successfully!');
    console.log('Interactive elements are ready.');
    
    // Initial scroll reveal check
    revealOnScroll();
});

// ===== READING TIME ESTIMATOR =====
// Calculate and display estimated reading time
function calculateReadingTime() {
    const content = document.querySelector('.content');
    const text = content.textContent;
    const wordCount = text.trim().split(/\s+/).length;
    const readingTime = Math.ceil(wordCount / 200); // Average reading speed: 200 words/min
    
    console.log(`Estimated reading time: ${readingTime} minute(s)`);
    console.log(`Word count: ${wordCount} words`);
}

calculateReadingTime();