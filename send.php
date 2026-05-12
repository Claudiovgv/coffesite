<?php
/**
 * Atlantic Crown Coffee — Contact Form Handler
 * Receives POST from the contact form and sends email via PHP mail().
 * Host: cpp77.webserver.pt (cPanel — sendmail is pre-configured)
 */

header('Content-Type: application/json; charset=utf-8');

// --- Only accept POST ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// --- Read and sanitise fields ---
function clean(string $value): string {
    return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
}

$type    = clean($_POST['type']    ?? 'contact');
$name    = clean($_POST['name']    ?? '');
$email   = clean($_POST['email']   ?? '');
$phone   = clean($_POST['phone']   ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

$to = 'veracruz@atlanticrowngroup.com';

// --- Product order ---
if ($type === 'order') {
    $coffee = clean($_POST['coffee'] ?? '');
    $size   = clean($_POST['size']   ?? '');
    $qty    = clean($_POST['qty']    ?? '');
    $notes  = clean($_POST['notes']  ?? '');

    if (empty($name) || empty($email) || empty($qty)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
        exit;
    }

    $mailSubject = '[Atlantic Crown] Product Order Request — ' . $coffee . ' ' . $size . ' from ' . $name;
    $body  = "New product order request via Atlantic Crown Coffee website.\n";
    $body .= "---------------------------------------------------------------\n";
    $body .= "Name:     {$name}\n";
    $body .= "Email:    {$email}\n";
    $body .= "Phone:    {$phone}\n";
    $body .= "---------------------------------------------------------------\n";
    $body .= "Coffee:   {$coffee}\n";
    $body .= "Format:   {$size}\n";
    $body .= "Quantity: {$qty}\n";
    $body .= "---------------------------------------------------------------\n\n";
    $body .= "Notes:\n" . ($notes ?: '—') . "\n\n";
    $body .= "---------------------------------------------------------------\n";
    $body .= "Sent from: atlanticcrowncoffee.com\n";

// --- Contact form ---
} else {
    $subject = clean($_POST['subject'] ?? '');
    $message = clean($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
        exit;
    }

    $mailSubject = '[Atlantic Crown] New contact from ' . $name;
    if (!empty($subject)) {
        $mailSubject .= ' — ' . $subject;
    }

    $body  = "You have received a new message via the Atlantic Crown Coffee website.\n";
    $body .= "---------------------------------------------------------------\n";
    $body .= "Name:    {$name}\n";
    $body .= "Email:   {$email}\n";
    $body .= "Phone:   {$phone}\n";
    $body .= "Subject: {$subject}\n";
    $body .= "---------------------------------------------------------------\n\n";
    $body .= $message . "\n\n";
    $body .= "---------------------------------------------------------------\n";
    $body .= "Sent from: atlanticcrowncoffee.com\n";
}

$headers  = "From: Atlantic Crown Website <no-reply@atlanticcrowncoffee.com>\r\n";
$headers .= "Reply-To: {$name} <{$email}>\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

// --- Send ---
$sent = mail($to, $mailSubject, $body, $headers);

if ($sent) {
    echo json_encode(['success' => true, 'message' => 'Your message has been sent. We will be in touch soon.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'There was a problem sending your message. Please try again or email us directly.']);
}
