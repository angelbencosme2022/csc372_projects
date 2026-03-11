<?php
$activePage = 'contact';

$formStatus  = '';
$statusClass = '';
$formData    = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name    = trim(htmlspecialchars($_POST['name']    ?? ''));
    $email   = trim(htmlspecialchars($_POST['email']   ?? ''));
    $subject = trim(htmlspecialchars($_POST['subject'] ?? ''));
    $message = trim(htmlspecialchars($_POST['message'] ?? ''));

    // Preserve values for re-population on error
    $formData = compact('name', 'email', 'subject', 'message');

    // Validation
    $errors = [];
    if (empty($name))    $errors[] = 'Name is required.';
    if (empty($email) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        $errors[] = 'A valid email address is required.';
    if (empty($subject)) $errors[] = 'Please select a subject.';
    if (empty($message)) $errors[] = 'Message cannot be empty.';

    if (empty($errors)) {
        // --- Send email ---
        // Replace with your actual address or swap in a mailer library (PHPMailer, etc.)
        $to      = 'hello@401thrift.com';
        $headers = "From: $name <$email>\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8";
        $body    = "Subject: $subject\n\nFrom: $name ($email)\n\n$message";

        if (mail($to, "401 Thrift Contact: $subject", $body, $headers)) {
            $formStatus  = 'Thank you! Your message has been sent. We\'ll get back to you within 24 hours.';
            $statusClass = 'success';
            $formData    = ['name' => '', 'email' => '', 'subject' => '', 'message' => '']; // clear form
        } else {
            $formStatus  = 'Sorry, there was a problem sending your message. Please try emailing us directly at hello@401thrift.com.';
            $statusClass = 'error';
        }
    } else {
        $formStatus  = implode(' ', $errors);
        $statusClass = 'error';
    }
}

$validSubjects = ['general', 'item', 'order', 'selling', 'bidding', 'other'];
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
        <p>
            Have questions about an item? Want to know when new inventory drops? Interested in selling your vintage 
            pieces to us? We'd love to hear from you! Fill out the form below and we'll get back to you as soon as 
            possible.
        </p>

        <h2>Send Us a Message</h2>

        <div class="contact-form-container">
            <?php if (!empty($formStatus)): ?>
                <p class="form-status <?= $statusClass ?>"><?= $formStatus ?></p>
            <?php endif; ?>

            <form method="POST" action="contact.php">
                <div class="form-group">
                    <label for="name">Your Name *</label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name"
                           value="<?= htmlspecialchars($formData['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" placeholder="your.email@example.com"
                           value="<?= htmlspecialchars($formData['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <select id="subject" name="subject" required>
                        <option value="">-- Select a subject --</option>
                        <?php
                        $subjectLabels = [
                            'general' => 'General Inquiry',
                            'item'    => 'Question About an Item',
                            'order'   => 'Order Status',
                            'selling' => 'I Want to Sell Items',
                            'bidding' => 'Bidding Question',
                            'other'   => 'Other',
                        ];
                        foreach ($subjectLabels as $val => $label):
                            $selected = ($formData['subject'] === $val) ? 'selected' : '';
                        ?>
                            <option value="<?= $val ?>" <?= $selected ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message">Your Message *</label>
                    <textarea id="message" name="message" rows="6"
                              placeholder="Tell us what's on your mind..." required><?= htmlspecialchars($formData['message']) ?></textarea>
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
                <p class="faq-answer">When you place a bid, you're entering an auction for that item. Auctions typically run 3-7 days. If someone outbids you, you'll receive a notification. The highest bidder when time runs out wins the item. Don't want to bid? Use the "Buy Now" option for instant purchase.</p>
            </div>
            <div class="faq-item">
                <h3 class="faq-question">Do you buy vintage items?</h3>
                <p class="faq-answer">Yes! We're always looking for quality vintage pieces. If you have items you'd like to sell, send us photos and descriptions via email or use the contact form above. We'll review and get back to you with an offer.</p>
            </div>
            <div class="faq-item">
                <h3 class="faq-question">Are the items authentic?</h3>
                <p class="faq-answer">Absolutely. We carefully authenticate all branded items and provide detailed condition reports. If we can't verify authenticity, we don't list it. Your trust is important to us.</p>
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