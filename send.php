<?php
// send.php — simple, robust handler for Beauty by Ksenia
// Supports: POST {name, phone, zone}
// Responds with JSON for AJAX requests, or minimal HTML fallback otherwise.

// ===== Config =====
$TO_EMAIL = 'info@beautybyksenia.com';
$FROM_EMAIL = 'no-reply@beautybyksenia.com'; // safe default for mail() on same domain
$SITE_NAME = 'Beauty by Ksenia';

mb_internal_encoding('UTF-8');

// ===== Helpers =====
function is_ajax_request() {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        return true;
    }
    if (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        return true;
    }
    if (!empty($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        return true;
    }
    return false;
}

function json_response($status_code, $payload) {
    http_response_code($status_code);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function html_response($status_code, $title, $message) {
    http_response_code($status_code);
    header('Content-Type: text/html; charset=UTF-8');
    ?>
    <!doctype html>
    <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
      <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif; background:#f7f7f8; color:#111; margin:0; padding:0; }
        .wrap { max-width: 640px; margin: 10vh auto; background:#fff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,.08); padding: 24px; }
        h1 { margin: 0 0 8px; font-size: 22px; }
        p { margin: 4px 0 0; color:#333; }
        .actions { margin-top: 16px; display:flex; gap:10px; }
        a, button { appearance:none; border:1px solid #ddd; background:#fff; padding:10px 14px; border-radius:10px; cursor:pointer; text-decoration:none; color:#111; }
        a:hover, button:hover { border-color:#ec4899; color:#ec4899; }
      </style>
    </head>
    <body>
      <div class="wrap">
        <h1><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <div class="actions">
          <a href="/" aria-label="Back to Home">Home</a>
          <button onclick="window.history.back()">Go Back</button>
        </div>
      </div>
    </body>
    </html>
    <?php
    exit;
}

// ===== Method & CSRF-lite checks =====
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if (is_ajax_request()) {
        json_response(405, ['ok' => false, 'error' => 'Method Not Allowed']);
    } else {
        html_response(405, 'Method Not Allowed', 'Please submit the form using POST.');
    }
}

// Simple honeypot: add a hidden input named "website" in your form; real users leave it empty.
$honeypot = isset($_POST['website']) ? trim((string)$_POST['website']) : '';
if ($honeypot !== '') {
    // silently accept to not tip off bots
    if (is_ajax_request()) {
        json_response(200, ['ok' => true, 'message' => 'Thank you!']);
    } else {
        html_response(200, 'Thank you', 'Your message has been received.');
    }
}

// ===== Input =====
$name = isset($_POST['name']) ? trim((string)$_POST['name']) : '';
$phone = isset($_POST['phone']) ? trim((string)$_POST['phone']) : '';
$zone = isset($_POST['zone']) ? trim((string)$_POST['zone']) : '';

// Basic normalization
$zone = strtolower($zone);
$allowed_zones = ['brows', 'lips', 'eyeliner'];
if (!in_array($zone, $allowed_zones, true)) {
    $zone = 'unspecified';
}

// Basic validation
$errors = [];
if ($name === '') { $errors['name'] = 'Name is required.'; }
if ($phone === '') { $errors['phone'] = 'Phone is required.'; }

// Light phone cleanup (digits, +, -, spaces, parentheses)
$phone_clean = preg_replace('/[^0-9+\-\s\(\)]/', '', $phone);
if ($phone_clean === '') { $errors['phone'] = 'Phone looks invalid.'; }

if (!empty($errors)) {
    if (is_ajax_request()) {
        json_response(400, ['ok' => false, 'errors' => $errors]);
    } else {
        html_response(400, 'Form Error', 'Please check the fields and try again.');
    }
}

// ===== Build email =====
$subject = 'New booking request — ' . $SITE_NAME;
$time = date('Y-m-d H:i:s');
$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';

$body_lines = [
    "New booking request from {$SITE_NAME}",
    "Time: {$time}",
    "Name: {$name}",
    "Phone: {$phone_clean}",
    "Service: {$zone}",
    "IP: {$ip}",
];
$body_text = implode("\n", $body_lines);

// Headers
$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'From: ' . $SITE_NAME . " <{$FROM_EMAIL}>";
$headers[] = 'Reply-To: ' . ($name !== '' ? $name : 'Client') . " <{$FROM_EMAIL}>";
$headers_str = implode("\r\n", $headers);

// ===== Send =====
$sent = @mail($TO_EMAIL, '=?UTF-8?B?'.base64_encode($subject).'?=', $body_text, $headers_str);

if ($sent) {
    if (is_ajax_request()) {
        json_response(200, ['ok' => true, 'message' => 'Thank you! We will contact you shortly.']);
    } else {
        html_response(200, 'Thank you', 'Your request has been sent. We will contact you shortly.');
    }
} else {
    if (is_ajax_request()) {
        json_response(500, ['ok' => false, 'error' => 'Failed to send email on server.']);
    } else {
        html_response(500, 'Server Error', 'Sorry, we could not send your message. Please contact us by phone or WhatsApp.');
    }
}
