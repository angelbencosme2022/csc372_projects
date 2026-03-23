<?php
/**
 * 
 * Description: Contact page for 401 Thrift. Collects visitor messages via a form,
 *              validates all inputs using shared validation functions, and sends
 *              an email on success. Also tracks visit count (cookie) and last
 *              visit time (session) per Part III requirements.
 *
 * Part II:  Form validation with external include file (includes/validate.php).
 * Part III: Cookie (visit_count) and session (last_visit, session message count) usage.
 */

// ── Part III: session & cookie setup ──────────────────────────────────────────
session_start();

// Allow visitor to end their session (Part III: terminate session requirement)
if (isset($_GET['end_session'])) {
    $_SESSION = [];                                          // clear session data
    session_destroy();                                       // destroy server-side session
    setcookie(session_name(), '', time() - 3600, '/');       // expire PHPSESSID cookie
    header('Location: contact.php');
    exit;
}

// Cookie: track total visits to the contact page
$visitCount = isset($_COOKIE['contact_visits']) ? (int) $_COOKIE['contact_visits'] + 1 : 1;
setcookie('contact_visits', $visitCount, time() + (60 * 60 * 24 * 30), '/'); // 30 days

// Session: record timestamp of this visit and count messages sent this session
if (!isset($_SESSION['contact_first_visit'])) {
    $_SESSION['contact_first_visit'] = date('F j, Y g:i A');
}
if (!isset($_SESSION['messages_sent'])) {
    $_SESSION['messages_sent'] = 0;
}

// ── Part II: form validation setup ────────────────────────────────────────────
require_once __DIR__ . '/includes/validate.php';

$activePage = 'contact';

$validSubjects = ['general', 'item', 'order', 'selling', 'bidding', 'other'];

// Initial values for each form control
$formData = [
    'name'    => '',
    'email'   => '',
    'subject' => '',
    'message' => '',
];

// Error messages for each control (blank = no error)
$errors = [
    'name'    => '',
    'email'   => '',
    'subject' => '',
    'message' => '',
];

$formStatus  = '';
$statusClass = '';

// ── Handle POST submission ────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect submitted values (use null-coalescing for options)
    $formData['name']    = trim($_POST['name']    ?? '');
    $formData['email']   = trim($_POST['email']   ?? '');
    $formData['subject'] = $_POST['subject']      ?? '';   // option — may be absent
    $formData['message'] = trim($_POST['message'] ?? '');

    // Validate: name (text, 2–100 chars)
    if (!validateText($formData['name'], 2, 100)) {
        $errors['name'] = 'Name must be between 2 and 100 characters.';
    }

    // Validate: email (text present + PHP email filter)
    if (!validateText($formData['email'], 3, 254) || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'A valid email address is required.';
    }

    // Validate: subject (must be one of the allowed options)
    if (!validateOption($formData['subject'], $validSubjects)) {
        $errors['subject'] = 'Please select a valid subject.';
    }

    // Validate: message (text, 10–2000 chars)
    if (!validateText($formData['message'], 10, 2000)) {
        $errors['message'] = 'Message must be between 10 and 2000 characters.';
    }

    // Combine all error messages to decide if form is valid
    $allErrors = implode(' ', array_filter($errors));

    if (empty($allErrors)) {
        // Escape before use in email body
        $safeName    = htmlspecialchars($formData['name']);
        $safeEmail   = htmlspecialchars($formData['email']);
        $safeSubject = htmlspecialchars($formData['subject']);
        $safeMessage = htmlspecialchars($formData['message']);

        $to      = 'hello@401thrift.com';
        $headers = "From: {$safeName} <{$safeEmail}>\r\nReply-To: {$safeEmail}\r\nContent-Type: text/plain; charset=UTF-8";
        $body    = "Subject: {$safeSubject}\n\nFrom: {$safeName} ({$safeEmail})\n\n{$safeMessage}";

        if (mail($to, "401 Thrift Contact: {$safeSubject}", $body, $headers)) {
            // Part III: increment session message counter
            $_SESSION['messages_sent']++;

            $formStatus  = 'Thank you! Your message has been sent. We\'ll get back to you within 24 hours.';
            $statusClass = 'success';
            $formData    = ['name' => '', 'email' => '', 'subject' => '', 'message' => '']; // clear form
        } else {
            $formStatus  = 'Sorry, there was a problem sending your message. Please try emailing us directly at hello@401thrift.com.';
            $statusClass = 'error';
        }
    } else {
        $formStatus  = $allErrors;
        $statusClass = 'error';
    }
}

$subjectLabels = [
    'general' => 'General Inquiry',
    'item'    => 'Question About an Item',
    'order'   => 'Order Status',
    'selling' => 'I Want to Sell Items',
    'bidding' => 'Bidding Question',
    'other'   => 'Other',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - 401 Thrift</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <div class="content">
        <h1>Get In Touch</h1>

        <!-- Part III: Display cookie + session visitor info -->
        <div class="visitor-info">
            <p>👋 You've visited this page <strong><?= htmlspecialchars((string)$visitCount) ?></strong> time<?= $visitCount !== 1 ? 's' : '' ?>.</p>
            <p>📅 Your session started: <strong><?= htmlspecialchars($_SESSION['contact_first_visit']) ?></strong></p>
            <?php if ($_SESSION['messages_sent'] > 0): ?>
                <p>✉️ Messages sent this session: <strong><?= (int) $_SESSION['messages_sent'] ?></strong></p>
            <?php endif; ?>
            <!-- Session end link (Part III requirement) -->
            <p><a href="contact.php?end_session=1" class="session-end-link">Clear my session data</a></p>
        </div>

        <p>
            Have questions about an item? Want to know when new inventory drops? Interested in selling your vintage
            pieces to us? We'd love to hear from you! Fill out the form below and we'll get back to you as soon as
            possible.
        </p>

        <h2>Send Us a Message</h2>

        <div class="contact-form-container">
            <?php if (!empty($formStatus)): ?>
                <p class="form-status <?= $statusClass ?>"><?= htmlspecialchars($formStatus) ?></p>
            <?php endif; ?>

            <form method="POST" action="contact.php">

                <!-- Name (text input) -->
                <div class="form-group">
                    <label for="name">Your Name * <small>(2–100 characters)</small></label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name"
                           value="<?= htmlspecialchars($formData['name']) ?>" required>
                    <?php if (!empty($errors['name'])): ?>
                        <span class="field-error"><?= htmlspecialchars($errors['name']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Email (text input) -->
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" placeholder="your.email@example.com"
                           value="<?= htmlspecialchars($formData['email']) ?>" required>
                    <?php if (!empty($errors['email'])): ?>
                        <span class="field-error"><?= htmlspecialchars($errors['email']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Subject (select / option) -->
                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <select id="subject" name="subject" required>
                        <option value="">-- Select a subject --</option>
                        <?php foreach ($subjectLabels as $val => $label):
                            $selected = ($formData['subject'] === $val) ? 'selected' : '';
                        ?>
                            <option value="<?= $val ?>" <?= $selected ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['subject'])): ?>
                        <span class="field-error"><?= htmlspecialchars($errors['subject']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Message (text / textarea) -->
                <div class="form-group">
                    <label for="message">Your Message * <small>(10–2000 characters)</small></label>
                    <textarea id="message" name="message" rows="6"
                              placeholder="Tell us what's on your mind..." required><?= htmlspecialchars($formData['message']) ?></textarea>
                    <?php if (!empty($errors['message'])): ?>
                        <span class="field-error"><?= htmlspecialchars($errors['message']) ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="submit-btn" id="submit-btn">Send Message</button>
            </form>
        </div>

        <h2>Other Ways to Reach Us</h2>
        <div class="contact-info-grid">
            <div class="contact-info-item">
                <h3>📧 Email</h3>
                <p><a href="mailto:hello@401thrift.com">hello@401thrift.com</a></p>
                <p>We typically respond within 24 hours.</p>
            </div>
            <div class="contact-info-item">
                <h3>📱 Social Media</h3>
                <p>Follow us on Instagram: <a href="#" target="_blank">@401thrift</a></p>
                <p>DM us for quick questions!</p>
            </div>
            <div class="contact-info-item">
                <h3>⏰ Response Time</h3>
                <p>Monday - Friday: 9am - 6pm EST</p>
                <p>Weekend messages answered on Monday</p>
            </div>
        </div>

        <h2>Frequently Asked Questions</h2>
        <div class="faq-section">
            <div class="faq-item">
                <h3 class="faq-question">How long does shipping take?</h3>
                <p class="faq-answer">We ship within 1-2 business days of purchase. Delivery typically takes 3-5 business days depending on your location. You'll receive tracking information once your order ships.</p>
            </div>
            <div class="faq-item">
                <h3 class="faq-question">What if an item doesn't fit?</h3>
                <p class="faq-answer">We accept returns within 7 days of delivery. Items must be unworn and in the same condition you received them. Contact us to initiate a return, and we'll provide a prepaid shipping label.</p>
            </div>
            <div class="faq-item">
                <h3 class="faq-question">How does bidding work?</h3>
                <p class="faq-answer">When you place a bid, you're entering an auction for that item. Auctions typically run 3-7 days. If someone outbids you, you'll receive a notification. The highest bidder when time runs out wins the item.</p>
            </div>
            <div class="faq-item">
                <h3 class="faq-question">Do you buy vintage items?</h3>
                <p class="faq-answer">Yes! We're always looking for quality vintage pieces. If you have items you'd like to sell, send us photos and descriptions via email or use the contact form above.</p>
            </div>
            <div class="faq-item">
                <h3 class="faq-question">Are the items authentic?</h3>
                <p class="faq-answer">Absolutely. We carefully authenticate all branded items and provide detailed condition reports. If we can't verify authenticity, we don't list it.</p>
            </div>
        </div>

        <h2>Visit Us</h2>
        <p>
            While 401 Thrift operates primarily online, we occasionally host pop-up shops and local events.
            Follow us on social media to stay updated on upcoming events where you can see items in person,
            meet the team, and discover exclusive finds!
        </p>
    </div>

    <script src="js/contact.js"></script>
</body>
</html>