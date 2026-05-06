<?php
require_once __DIR__ . '/validate.php';
require_once __DIR__ . '/repository.php';

function thriftContactSubjectLabels(): array
{
    return [
        'general' => 'General Inquiry',
        'item' => 'Question About an Item',
        'order' => 'Order Status',
        'selling' => 'I Want to Sell Items',
        'bidding' => 'Bidding Question',
        'other' => 'Other',
    ];
}

function thriftEmailConfig(): array
{
    $fileConfig = [];
    $configPath = __DIR__ . '/email-config.php';

    if (is_file($configPath)) {
        $loaded = require $configPath;
        if (is_array($loaded)) {
            $fileConfig = $loaded;
        }
    }

    $recipient = trim((string)($fileConfig['contact_recipient'] ?? getenv('THRIFT_CONTACT_RECIPIENT') ?: 'hello@401thrift.com'));

    return [
        'provider' => trim((string)($fileConfig['provider'] ?? getenv('THRIFT_EMAIL_PROVIDER') ?: 'resend')),
        'api_key' => trim((string)($fileConfig['api_key'] ?? getenv('THRIFT_EMAIL_API_KEY') ?: getenv('THRIFT_RESEND_API_KEY') ?: '')),
        'from' => trim((string)($fileConfig['from'] ?? getenv('THRIFT_EMAIL_FROM') ?: '')),
        'contact_recipient' => $recipient,
        'reply_to' => trim((string)($fileConfig['reply_to'] ?? getenv('THRIFT_EMAIL_REPLY_TO') ?: $recipient)),
        'endpoint' => trim((string)($fileConfig['endpoint'] ?? getenv('THRIFT_EMAIL_API_ENDPOINT') ?: 'https://api.resend.com/emails')),
    ];
}

function thriftEmailConfigured(): bool
{
    $config = thriftEmailConfig();

    return $config['provider'] === 'resend'
        && $config['api_key'] !== ''
        && $config['from'] !== ''
        && $config['contact_recipient'] !== '';
}

function thriftValidateContactPayload(array $payload): array
{
    $subjectLabels = thriftContactSubjectLabels();
    $validSubjects = array_keys($subjectLabels);
    $errors = [];

    if (!validateText(trim((string)($payload['name'] ?? '')), 2, 100)) {
        $errors['name'] = 'Name must be between 2 and 100 characters.';
    }

    $email = trim((string)($payload['email'] ?? ''));
    if (!validateText($email, 3, 254) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'A valid email address is required.';
    }

    if (!validateOption((string)($payload['subject'] ?? ''), $validSubjects)) {
        $errors['subject'] = 'Please select a valid subject.';
    }

    if (!validateText(trim((string)($payload['message'] ?? '')), 10, 2000)) {
        $errors['message'] = 'Message must be between 10 and 2000 characters.';
    }

    return $errors;
}

function thriftSendEmailViaApi(array $payload): array
{
    $config = thriftEmailConfig();

    if (!thriftEmailConfigured()) {
        return [
            'success' => false,
            'error' => 'Email API is not configured.',
        ];
    }

    $requestBody = json_encode($payload, JSON_UNESCAPED_SLASHES);
    if ($requestBody === false) {
        return [
            'success' => false,
            'error' => 'Could not encode the email payload.',
        ];
    }

    $headers = [
        'Authorization: Bearer ' . $config['api_key'],
        'Content-Type: application/json',
        'Accept: application/json',
        'User-Agent: 401thrift-contact/1.0',
    ];

    if (function_exists('curl_init')) {
        $ch = curl_init($config['endpoint']);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $requestBody,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
        ]);

        $rawResponse = curl_exec($ch);
        $statusCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($rawResponse === false || $curlError !== '') {
            return [
                'success' => false,
                'error' => $curlError !== '' ? $curlError : 'The email API request failed.',
            ];
        }
    } else {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", $headers),
                'content' => $requestBody,
                'timeout' => 15,
                'ignore_errors' => true,
            ],
        ]);

        $rawResponse = file_get_contents($config['endpoint'], false, $context);
        $statusCode = 0;

        if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $matches)) {
            $statusCode = (int)$matches[1];
        }

        if ($rawResponse === false) {
            return [
                'success' => false,
                'error' => 'The email API request failed.',
            ];
        }
    }

    $decodedResponse = json_decode($rawResponse, true);

    if ($statusCode < 200 || $statusCode >= 300) {
        $message = is_array($decodedResponse) && isset($decodedResponse['message'])
            ? (string)$decodedResponse['message']
            : 'The email API returned an error.';

        return [
            'success' => false,
            'error' => $message,
            'status' => $statusCode,
        ];
    }

    return [
        'success' => true,
        'data' => is_array($decodedResponse) ? $decodedResponse : [],
        'status' => $statusCode,
    ];
}

function thriftSendContactEmails(array $payload): array
{
    $config = thriftEmailConfig();
    $subjectLabels = thriftContactSubjectLabels();
    $subjectKey = (string)$payload['subject'];
    $subjectLabel = $subjectLabels[$subjectKey] ?? ucfirst($subjectKey);
    $safeName = htmlspecialchars((string)$payload['name'], ENT_QUOTES, 'UTF-8');
    $safeEmail = htmlspecialchars((string)$payload['email'], ENT_QUOTES, 'UTF-8');
    $safeMessage = nl2br(htmlspecialchars((string)$payload['message'], ENT_QUOTES, 'UTF-8'));

    $notification = thriftSendEmailViaApi([
        'from' => $config['from'],
        'to' => [$config['contact_recipient']],
        'reply_to' => [$payload['email']],
        'subject' => '401 Thrift Contact: ' . $subjectLabel,
        'html' => "<p><strong>Name:</strong> {$safeName}</p><p><strong>Email:</strong> {$safeEmail}</p><p><strong>Subject:</strong> {$subjectLabel}</p><p><strong>Message:</strong><br>{$safeMessage}</p>",
        'text' => "Name: {$payload['name']}\nEmail: {$payload['email']}\nSubject: {$subjectLabel}\n\n{$payload['message']}",
    ]);

    if (!$notification['success']) {
        return $notification;
    }

    $reply = thriftSendEmailViaApi([
        'from' => $config['from'],
        'to' => [$payload['email']],
        'reply_to' => [$config['reply_to']],
        'subject' => 'Thanks for contacting 401 Thrift',
        'html' => '<p>Thanks for the interest. We will contact you shortly.</p><p>We received your message and will follow up as soon as possible.</p>',
        'text' => "Thanks for the interest. We will contact you shortly.\n\nWe received your message and will follow up as soon as possible.",
    ]);

    return $reply;
}

function thriftProcessContactSubmission(array $payload): array
{
    $cleanPayload = [
        'name' => trim((string)($payload['name'] ?? '')),
        'email' => trim((string)($payload['email'] ?? '')),
        'subject' => trim((string)($payload['subject'] ?? '')),
        'message' => trim((string)($payload['message'] ?? '')),
    ];

    $errors = thriftValidateContactPayload($cleanPayload);
    if ($errors) {
        return [
            'success' => false,
            'status' => 422,
            'errors' => $errors,
            'message' => 'Please correct the highlighted fields and try again.',
        ];
    }

    $storedInDatabase = false;
    if (thriftDbConfigured()) {
        try {
            saveContactMessage($cleanPayload);
            $storedInDatabase = true;
        } catch (Throwable $e) {
            $storedInDatabase = false;
        }
    }

    $emailResult = thriftSendContactEmails($cleanPayload);
    if (!$emailResult['success']) {
        if ($storedInDatabase) {
            return [
                'success' => true,
                'status' => 200,
                'errors' => [],
                'message' => 'Thanks for the interest. We received your message and will contact you shortly.',
                'warning' => 'Your message was saved, but the confirmation email could not be sent right now.',
            ];
        }

        return [
            'success' => false,
            'status' => 500,
            'errors' => [],
            'message' => 'We could not send your message right now. Please try again in a moment.',
            'detail' => $emailResult['error'] ?? 'Email delivery failed.',
        ];
    }

    return [
        'success' => true,
        'status' => 200,
        'errors' => [],
        'message' => 'Thanks for the interest. We will contact you shortly.',
    ];
}
