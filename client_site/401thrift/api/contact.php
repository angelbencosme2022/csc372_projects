<?php
require_once __DIR__ . '/../includes/api.php';
require_once __DIR__ . '/../includes/contact-service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'error' => 'Method not allowed.'], 405);
}

$payload = readJsonInput();
if ($payload === []) {
    $payload = $_POST;
}

$result = thriftProcessContactSubmission($payload);

$response = [
    'success' => $result['success'],
    'message' => $result['message'],
];

if (!empty($result['errors'])) {
    $response['errors'] = $result['errors'];
}

if (!empty($result['warning'])) {
    $response['warning'] = $result['warning'];
}

if (!empty($result['detail'])) {
    $response['detail'] = $result['detail'];
}

jsonResponse($response, $result['status'] ?? 200);
