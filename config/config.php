<?php
// ====================== MARTIN VERSA EMAIL SENDER CONFIGURATION ======================

// Email Content Configuration
$subjectconf = [
    'Subject [ID : ##random## ]' // Support UTF-8
];

$fnameconf = [
    'From Name'  // Support UTF-8
];

$fmailconf = [];
$fmPath = __DIR__.'/fm.txt';

if (file_exists($fmPath)) {
    $fmailconf = array_filter(
        file($fmPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES),
        function($email) {
            return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
        }
    );
}

$shortlinkconf = [
    'https://google.com?id=##random##'
];

$customheaderconf = [
    // Example usage:
    // 'X-Mailer' => 'Microsoft Outlook 16.0',
    // 'Return-Path' => 'bounce@example.com',
    // 'Reply-To' => 'support@example.com',
];

// ====================== SYSTEM CONFIGURATION ======================
define('TEMPLATE_FILE', __DIR__.'/../letter/letter.txt');
define('RECIPIENTS_FILE', __DIR__.'/../data/list.txt');
define('SMTP_FILE', __DIR__.'/../data/smtp.txt');

// Sending Options
define('OPERATION_MODE', 'to'); // 'to' or 'bcc'
define('DELAY_SECONDS', 1);
define('MAX_BCC', 10);

// Template Placeholders
define('PLACEHOLDER_PREFIX', '##');
define('PLACEHOLDER_SUFFIX', '##');
