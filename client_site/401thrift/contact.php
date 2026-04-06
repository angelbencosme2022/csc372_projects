<?php
/**
 * Contact page — 401 Thrift
 *
 * PHP handles:
 *   - Session (last visit time, messages sent this session)
 *   - Cookie  (visit count over 30 days)
 *   - Server-side form validation via includes/validate.php
 *   - Sending email on valid submission
 */

session_start();

// Allow visitor to clear their session
if (isset($_GET['end_session'])) {
    $_SESSION = [];
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');
    header('Location: contact.php');
    exit;
}

// Cookie: total visits to contact page (30 days)
$visitCount = isset($_COOKIE['contact_visits']) ? (int)$_COOKIE['contact_visits'] + 1 : 1;
setcookie('contact_visits', $visitCount, time() + 60 * 60 * 24 * 30, '/');

// Session: first visit timestamp + messages sent counter
if (!isset($_SESSION['contact_first_visit'])) {
    $_SESSION['contact_first_visit'] = date('F j, Y g:i A');
}
if (!isset($_SESSION['messages_sent'])) {
    $_SESSION['messages_sent'] = 0;
}

require_once __DIR__ . '/includes/validate.php';
require_once __DIR__ . '/includes/cart.php';   // for nav cart count
require_once __DIR__ . '/includes/repository.php';

$activePage    = 'contact';
$validSubjects = ['general', 'item', 'order', 'selling', 'bidding', 'other'];

$formData = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
$errors   = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
$formStatus  = '';
$statusClass = '';

// ── Handle POST ───────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['name']    = trim($_POST['name']    ?? '');
    $formData['email']   = trim($_POST['email']   ?? '');
    $formData['subject'] = $_POST['subject']      ?? '';
    $formData['message'] = trim($_POST['message'] ?? '');

    if (!validateText($formData['name'], 2, 100)) {
        $errors['name'] = 'Name must be between 2 and 100 characters.';
    }
    if (!validateText($formData['email'], 3, 254) || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'A valid email address is required.';
    }
    if (!validateOption($formData['subject'], $validSubjects)) {
        $errors['subject'] = 'Please select a valid subject.';
    }
    if (!validateText($formData['message'], 10, 2000)) {
        $errors['message'] = 'Message must be between 10 and 2000 characters.';
    }

    if (empty(implode('', array_filter($errors)))) {
        if (thriftDbConfigured()) {
            try {
                saveContactMessage($formData);
            } catch (Throwable $e) {
                $formStatus = 'Sorry, we could not save your message right now. Please try again in a moment.';
                $statusClass = 'error';
            }
        }

        if ($statusClass !== 'error') {
            $safeName    = htmlspecialchars($formData['name']);
            $safeEmail   = htmlspecialchars($formData['email']);
            $safeSubject = htmlspecialchars($formData['subject']);
            $safeMessage = htmlspecialchars($formData['message']);

            $to      = 'hello@401thrift.com';
            $headers = "From: {$safeName} <{$safeEmail}>\r\nReply-To: {$safeEmail}\r\nContent-Type: text/plain; charset=UTF-8";
            $body    = "Subject: {$safeSubject}\n\nFrom: {$safeName} ({$safeEmail})\n\n{$safeMessage}";

            if (mail($to, "401 Thrift Contact: {$safeSubject}", $body, $headers)) {
                $_SESSION['messages_sent']++;
                $formStatus  = "Thank you! Your message has been sent. We'll get back to you within 24 hours.";
                $statusClass = 'success';
                $formData    = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
            } else {
                $formStatus  = thriftDbConfigured()
                    ? 'Message saved, but email delivery is not configured on this machine. Your submission is still stored in the database.'
                    : 'Email delivery is not configured on this machine. Configure your database and mail settings on cPanel before going live.';
                $statusClass = 'success';
                $_SESSION['messages_sent']++;
                $formData    = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
            }
        }
    } else {
        $formStatus  = implode(' ', array_filter($errors));
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
    <title>Contact Us — 401 Thrift</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <div class="content">
        <h1>Get In Touch</h1>

        <!-- Session / Cookie visitor info -->
        <div class="visitor-info">
            <p>👋 You've visited this page <strong><?= htmlspecialchars((string)$visitCount) ?></strong>
               time<?= $visitCount !== 1 ? 's' : '' ?>.</p>
            <p>📅 Your session started: <strong><?= htmlspecialchars($_SESSION['contact_first_visit']) ?></strong></p>
            <?php if ($_SESSION['messages_sent'] > 0): ?>
                <p>✉️ Messages sent this session: <strong><?= (int)$_SESSION['messages_sent'] ?></strong></p>
            <?php endif; ?>
            <p><a href="contact.php?end_session=1" class="session-end-link">Clear my session data</a></p>
        </div>

        <p>
            Have questions about an item? Interested in selling your vintage pieces to us? We'd love to hear from you.
            Fill out the form below and we'll get back to you as soon as possible.
        </p>

        <h2>Send Us a Message</h2>

        <div class="contact-form-container">
            <?php if (!empty($formStatus)): ?>
                <p class="form-status <?= $statusClass ?>"><?= htmlspecialchars($formStatus) ?></p>
            <?php endif; ?>

            <form method="POST" action="contact.php">

                <div class="form-group">
                    <label for="name">Your Name * <small>(2–100 characters)</small></label>
                    <input type="text" id="name" name="name"
                           placeholder="Enter your full name"
                           value="<?= htmlspecialchars($formData['name']) ?>" required>
                    <?php if (!empty($errors['name'])): ?>
                        <span class="field-error"><?= htmlspecialchars($errors['name']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email"
                           placeholder="your.email@example.com"
                           value="<?= htmlspecialchars($formData['email']) ?>" required>
                    <?php if (!empty($errors['email'])): ?>
                        <span class="field-error"><?= htmlspecialchars($errors['email']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <select id="subject" name="subject" required>
                        <option value="">-- Select a subject --</option>
                        <?php foreach ($subjectLabels as $val => $label):
                            $sel = ($formData['subject'] === $val) ? 'selected' : '';
                        ?>
                            <option value="<?= $val ?>" <?= $sel ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['subject'])): ?>
                        <span class="field-error"><?= htmlspecialchars($errors['subject']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="message">Your Message * <small>(10–2000 characters)</small></label>
                    <textarea id="message" name="message" rows="6"
                              placeholder="Tell us what's on your mind..." required><?= htmlspecialchars($formData['message']) ?></textarea>
                    <?php if (!empty($errors['message'])): ?>
                        <span class="field-error"><?= htmlspecialchars($errors['message']) ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="submit-btn">Send Message</button>
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
                <p>Monday – Friday: 9am – 6pm EST</p>
                <p>Weekend messages answered Monday</p>
            </div>
        </div>

        <h2>Frequently Asked Questions</h2>
        <div class="faq-section">
            <div class="faq-item">
                <h3 class="faq-question">How long does shipping take?</h3>
                <p class="faq-answer">We ship within 1–2 business days. Delivery typically takes 3–5 business days. You'll receive tracking info once your order ships.</p>
            </div>
            <div class="faq-item">
                <h3 class="faq-question">What if an item doesn't fit?</h3>
                <p class="faq-answer">We accept returns within 7 days of delivery. Items must be unworn and in original condition. Contact us to start a return.</p>
            </div>
            <div class="faq-item">
                <h3 class="faq-question">How does bidding work?</h3>
                <p class="faq-answer">When you place a bid you're entering an auction. Auctions run 3–7 days. The highest bidder when time runs out wins the item.</p>
            </div>
            <div class="faq-item">
                <h3 class="faq-question">Do you buy vintage items?</h3>
                <p class="faq-answer">Yes! Send us photos and descriptions via email or the contact form above.</p>
            </div>
            <div class="faq-item">
                <h3 class="faq-question">Are the items authentic?</h3>
                <p class="faq-answer">Absolutely. We authenticate all branded items and provide detailed condition reports. If we can't verify authenticity, we don't list it.</p>
            </div>
        </div>

        <h2>Visit Us</h2>
        <p>
            While 401 Thrift operates primarily online, we occasionally host pop-up shops and local events.
            Follow us on social media to stay updated!
        </p>
    </div>

    <footer class="footer">
        <p>&copy; <?= date('Y') ?> 401 Thrift &mdash; Sustainable fashion, one find at a time.</p>
    </footer>

    <script>
        // FAQ accordion
        document.querySelectorAll('.faq-question').forEach(q => {
            q.addEventListener('click', () => {
                const answer = q.nextElementSibling;
                const isOpen = q.classList.contains('open');
                // Close all
                document.querySelectorAll('.faq-question').forEach(x => x.classList.remove('open'));
                document.querySelectorAll('.faq-answer').forEach(x => x.classList.remove('open'));
                // Toggle clicked
                if (!isOpen) {
                    q.classList.add('open');
                    answer.classList.add('open');
                }
            });
        });
    </script>
</body>
</html>
