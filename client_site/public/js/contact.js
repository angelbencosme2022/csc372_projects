/* 
   Name: [Your Name]
   Date: February 7, 2026
   Description: JavaScript for 401 Thrift Contact page - handles form validation and submission
*/

// ===== SELECTING HTML ELEMENTS =====
const nameInput = document.getElementById('name');
const emailInput = document.getElementById('email');
const subjectSelect = document.getElementById('subject');
const messageTextarea = document.getElementById('message');
const submitButton = document.getElementById('submit-btn');
const formStatus = document.getElementById('form-status');
const faqQuestions = document.querySelectorAll('.faq-question');

// ===== FORM VALIDATION =====
// Validate email format
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Validate all form fields
function validateForm() {
    let isValid = true;
    let errorMessage = '';
    
    // Check name
    if (nameInput.value.trim() === '') {
        isValid = false;
        errorMessage += 'Please enter your name. ';
        nameInput.style.borderColor = '#e74c3c';
    } else {
        nameInput.style.borderColor = '#bdc3c7';
    }
    
    // Check email
    if (emailInput.value.trim() === '') {
        isValid = false;
        errorMessage += 'Please enter your email. ';
        emailInput.style.borderColor = '#e74c3c';
    } else if (!isValidEmail(emailInput.value)) {
        isValid = false;
        errorMessage += 'Please enter a valid email address. ';
        emailInput.style.borderColor = '#e74c3c';
    } else {
        emailInput.style.borderColor = '#bdc3c7';
    }
    
    // Check subject
    if (subjectSelect.value === '') {
        isValid = false;
        errorMessage += 'Please select a subject. ';
        subjectSelect.style.borderColor = '#e74c3c';
    } else {
        subjectSelect.style.borderColor = '#bdc3c7';
    }
    
    // Check message
    if (messageTextarea.value.trim() === '') {
        isValid = false;
        errorMessage += 'Please enter a message. ';
        messageTextarea.style.borderColor = '#e74c3c';
    } else if (messageTextarea.value.trim().length < 10) {
        isValid = false;
        errorMessage += 'Message must be at least 10 characters. ';
        messageTextarea.style.borderColor = '#e74c3c';
    } else {
        messageTextarea.style.borderColor = '#bdc3c7';
    }
    
    return { isValid, errorMessage };
}

// ===== FORM SUBMISSION =====
// Handle form submission
submitButton.addEventListener('click', function(event) {
    event.preventDefault();
    
    // Validate form
    const validation = validateForm();
    
    if (validation.isValid) {
        // Show loading state
        submitButton.textContent = 'Sending...';
        submitButton.disabled = true;
        submitButton.style.backgroundColor = '#95a5a6';
        
        // Simulate form submission (in real app, this would send to server)
        setTimeout(function() {
            // Success!
            formStatus.textContent = 'Thank you! Your message has been sent successfully. We\'ll get back to you soon!';
            formStatus.className = 'form-status success';
            
            // Reset form
            nameInput.value = '';
            emailInput.value = '';
            subjectSelect.value = '';
            messageTextarea.value = '';
            
            // Reset button
            submitButton.textContent = 'Send Message';
            submitButton.disabled = false;
            submitButton.style.backgroundColor = '#f39c12';
            
            // Log submission
            console.log('Form submitted successfully!');
            console.log(`Name: ${nameInput.value}`);
            console.log(`Email: ${emailInput.value}`);
            console.log(`Subject: ${subjectSelect.value}`);
            
            // Clear success message after 5 seconds
            setTimeout(function() {
                formStatus.textContent = '';
                formStatus.className = 'form-status';
            }, 5000);
            
        }, 1500);
        
    } else {
        // Show error message
        formStatus.textContent = validation.errorMessage;
        formStatus.className = 'form-status error';
        
        console.log('Form validation failed:', validation.errorMessage);
        
        // Clear error message after 5 seconds
        setTimeout(function() {
            formStatus.textContent = '';
            formStatus.className = 'form-status';
        }, 5000);
    }
});

// ===== REAL-TIME VALIDATION =====
// Add real-time validation feedback as user types
nameInput.addEventListener('blur', function() {
    if (this.value.trim() === '') {
        this.style.borderColor = '#e74c3c';
    } else {
        this.style.borderColor = '#27ae60';
    }
});

emailInput.addEventListener('blur', function() {
    if (this.value.trim() === '') {
        this.style.borderColor = '#e74c3c';
    } else if (!isValidEmail(this.value)) {
        this.style.borderColor = '#e74c3c';
    } else {
        this.style.borderColor = '#27ae60';
    }
});

subjectSelect.addEventListener('change', function() {
    if (this.value === '') {
        this.style.borderColor = '#e74c3c';
    } else {
        this.style.borderColor = '#27ae60';
    }
});

messageTextarea.addEventListener('blur', function() {
    if (this.value.trim() === '' || this.value.trim().length < 10) {
        this.style.borderColor = '#e74c3c';
    } else {
        this.style.borderColor = '#27ae60';
    }
});

// ===== CHARACTER COUNTER FOR MESSAGE =====
// Add character counter to message field
messageTextarea.addEventListener('input', function() {
    const charCount = this.value.length;
    const minChars = 10;
    
    if (charCount < minChars) {
        console.log(`Characters: ${charCount}/${minChars} (minimum)`);
    } else {
        console.log(`Characters: ${charCount}`);
    }
});

// ===== FAQ ACCORDION FUNCTIONALITY =====
// Toggle FAQ answers when questions are clicked
faqQuestions.forEach(function(question) {
    question.addEventListener('click', function() {
        const answer = this.nextElementSibling;
        const faqItem = this.closest('.faq-item');
        
        // Toggle visibility
        if (answer.style.display === 'none' || answer.style.display === '') {
            answer.style.display = 'block';
            faqItem.style.backgroundColor = '#fff3cd';
            this.style.color = '#f39c12';
        } else {
            answer.style.display = 'none';
            faqItem.style.backgroundColor = '#f9f9f9';
            this.style.color = '#2c3e50';
        }
        
        console.log(`FAQ toggled: ${this.textContent}`);
    });
    
    // Initially hide all answers
    const answer = question.nextElementSibling;
    answer.style.display = 'none';
});

// ===== PAGE LOAD ACTIONS =====
window.addEventListener('load', function() {
    console.log('Contact page loaded successfully!');
    console.log('Form validation is ready.');
    
    // Focus on name input
    nameInput.focus();
});

// ===== EMAIL LINK CLICK TRACKING =====
const emailLinks = document.querySelectorAll('a[href^="mailto:"]');
emailLinks.forEach(function(link) {
    link.addEventListener('click', function() {
        console.log('Email link clicked:', this.href);
    });
});