<?php
require_once __DIR__ . '/validate.php';

function jsonResponse(array $payload, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($payload);
    exit;
}

function readJsonInput(): array {
    $raw = file_get_contents('php://input');
    if ($raw === false || $raw === '') {
        return [];
    }

    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function validateCheckoutPayload(array $payload): array {
    $errors = [];

    if (!validateText(trim($payload['name'] ?? ''), 2, 100)) {
        $errors['name'] = 'Name must be between 2 and 100 characters.';
    }
    if (!filter_var(trim($payload['email'] ?? ''), FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'A valid email address is required.';
    }
    if (!validateText(trim($payload['address_line1'] ?? ''), 5, 150)) {
        $errors['address_line1'] = 'Address Line 1 is required.';
    }
    if (!validateText(trim($payload['city'] ?? ''), 2, 100)) {
        $errors['city'] = 'City is required.';
    }
    if (!validateText(trim($payload['state'] ?? ''), 2, 50)) {
        $errors['state'] = 'State is required.';
    }

    $zip = trim($payload['postal_code'] ?? '');
    if (!preg_match('/^\d{5}(-\d{4})?$/', $zip)) {
        $errors['postal_code'] = 'ZIP code must be valid.';
    }

    return $errors;
}
