<?php
require_once __DIR__.'/../config/config.php';

function loadData($filePath) {
    if (!file_exists($filePath)) return [];
    $data = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return array_filter($data, function($line) {
        return !str_starts_with(trim($line), '#');
    });
}

function validateEmail($email) {
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
}

function generateRandom($length = 8) {
    return bin2hex(random_bytes($length/2));
}

function processTemplate($content, $email) {
    global $shortlinkconf;
    
    // Convert content to UTF-8 if not already
    if (!mb_detect_encoding($content, 'UTF-8', true)) {
        $content = mb_convert_encoding($content, 'UTF-8');
    }
    
    $replacements = [
        'random' => generateRandom(),
        'email' => $email,
        'date' => date('Y-m-d'),
        'time' => date('H:i:s')
    ];
    
    // Process shortlinks
    $processedLink = str_replace(
        PLACEHOLDER_PREFIX.'random'.PLACEHOLDER_SUFFIX,
        $replacements['random'],
        $shortlinkconf[0]
    );
    $replacements['shortlink'] = $processedLink;
    
    // Process content with UTF-8 support
    foreach ($replacements as $key => $value) {
        $content = str_replace(
            PLACEHOLDER_PREFIX.$key.PLACEHOLDER_SUFFIX,
            $value,
            $content
        );
    }
    
    return $content;
}

function addCustomHeaders($mailer, $recipientEmail = '') {
    global $customheaderconf, $fmailconf;
    
    // Get a random sender email for headers
    $senderEmail = !empty($fmailconf) ? $fmailconf[array_rand($fmailconf)] : 'no-reply@example.com';
    $domain = substr(strrchr($senderEmail, "@"), 1);
    
    $replacements = [
        'fmail' => $senderEmail,
        'domainfmail' => $domain,
        'random' => generateRandom(),
        'randnum_100' => rand(1, 100),
        'rand_low_10' => rand(1, 10),
        'rand_ip' => rand(1,255).'.'.rand(1,255).'.'.rand(1,255).'.'.rand(1,255),
        'generateid' => generateRandom(16),
        'rand_ua' => 'UserAgent/'.rand(1,100).'.'.rand(1,10),
        'email' => $recipientEmail
    ];
    
    foreach ($customheaderconf as $header) {
        $parts = explode('|', $header, 2);
        if (count($parts) === 2) {
            $headerName = trim($parts[0]);
            $headerValue = trim($parts[1]);
            
            // Process placeholders in header value
            foreach ($replacements as $key => $value) {
                $headerValue = str_replace(
                    PLACEHOLDER_PREFIX.$key.PLACEHOLDER_SUFFIX,
                    $value,
                    $headerValue
                );
            }
            
            $mailer->addCustomHeader($headerName, $headerValue);
        }
    }
    
    // Additional UTF-8 header to ensure proper encoding
    $mailer->CharSet = 'UTF-8';
    $mailer->Encoding = 'base64';
}

function displayHeader() {
    system('cls || clear');
    echo "\033[38;5;213m
    ███╗   ███╗ █████╗ ██████╗ ████████╗██╗███╗   ██╗    ██╗   ██╗███████╗██████╗ ███████╗ █████╗ 
    ████╗ ████║██╔══██╗██╔══██╗╚══██╔══╝██║████╗  ██║    ██║   ██║██╔════╝██╔══██╗██╔════╝██╔══██╗
    ██╔████╔██║███████║██████╔╝   ██║   ██║██╔██╗ ██║    ██║   ██║█████╗  ██████╔╝███████╗███████║
    ██║╚██╔╝██║██╔══██║██╔══██╗   ██║   ██║██║╚██╗██║    ╚██╗ ██╔╝██╔══╝  ██╔══██╗╚════██║██╔══██║
    ██║ ╚═╝ ██║██║  ██║██║  ██║   ██║   ██║██║ ╚████║     ╚████╔╝ ███████╗██║  ██║███████║██║  ██║
    ╚═╝     ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝   ╚═╝╚═╝  ╚═══╝      ╚═══╝  ╚══════╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
    \033[0m\n";
    
    echo "\033[38;5;87m
    ╔══════════════════════════════════════════════════════════════════════════════════════════════╗
    ║ \033[1;38;5;231mMARTIN VERSA EMAIL SENDER v2.1\033[0;38;5;87m                                                               ║
    ╠══════════════════════════════════════════════════════════════════════════════════════════════╣
    ║ \033[38;5;51m» \033[38;5;231mAdvanced Email Delivery System with SMTP Rotation and Header Spoofing                      \033[38;5;87m║
    ║ \033[38;5;51m» \033[38;5;231mTelegram: \033[38;5;213m@martinversa\033[38;5;231m                                                                     \033[38;5;87m║
    ║ \033[38;5;51m» \033[38;5;231mMode: \033[1;38;5;213m".str_pad(strtoupper(OPERATION_MODE), 10)."\033[0;38;5;231m Max BCC: \033[1;38;5;213m".str_pad(MAX_BCC, 4)."\033[0;38;5;231m Delay: \033[1;38;5;213m".DELAY_SECONDS."s\033[0;38;5;231m                                                   \033[38;5;87m║
    ╚══════════════════════════════════════════════════════════════════════════════════════════════╝
    \033[0m\n\n";
    
    echo "\033[38;5;87m    [\033[38;5;51m".date('Y-m-d H:i:s')."\033[38;5;87m] \033[1;38;5;231mINITIALIZING SENDING SEQUENCE...\033[0m\n";
    echo "\033[38;5;87m    ".str_repeat("─", 100)."\033[0m\n";
}

function displayStats($success, $fail) {
    $total = $success + $fail;
    $successRate = ($total > 0) ? round(($success / $total) * 100, 2) : 0;
    
    echo "\n  \033[1;34mFINAL DELIVERY REPORT:\033[0m\n";
    echo "  ".str_repeat("-", 70)."\n";
    echo "  \033[1;32m✓ Success:\033[0m ".number_format($success)." emails (\033[1;32m{$successRate}%\033[0m)\n";
    echo "  \033[1;31m✗ Failed:\033[0m ".number_format($fail)." emails\n";
    echo "  \033[1;35m∑ Total:\033[0m ".number_format($total)." emails sent\n";
    echo "  ".str_repeat("-", 70)."\n";
}

function displayError($message) {
    echo "\n  \033[1;31m✗ ERROR: $message\033[0m\n";
    echo "  \033[1;34m".str_repeat("-", 100)."\033[0m\n";
    exit(1);
}

function maskEmail($email) {
    $parts = explode('@', $email);
    if (count($parts) !== 2) return $email;
    
    $username = $parts[0];
    $domain = $parts[1];
    
    // Mask username (show first 3 characters)
    $maskedUsername = substr($username, 0, 3).str_repeat('*', max(0, strlen($username) - 3));
    
    return $maskedUsername.'@'.$domain;
}