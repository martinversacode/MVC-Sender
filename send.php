<?php
require_once __DIR__.'/config/config.php';
require_once __DIR__.'/includes/functions.php';
require __DIR__.'/src/PHPMailer.php';
require __DIR__.'/src/SMTP.php';
require __DIR__.'/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialize
displayHeader();
$successCount = 0;
$failCount = 0;
$currentEmailIndex = 0; // Initialize email rotation index

// Load data
$recipients = array_filter(loadData(RECIPIENTS_FILE), 'validateEmail');
$smtpAccounts = loadData(SMTP_FILE);

// Validate
if (empty($recipients)) die(displayError("ERROR: No valid recipients found"));
if (empty($smtpAccounts)) die(displayError("ERROR: No SMTP accounts configured"));
if (!file_exists(TEMPLATE_FILE)) die(displayError("ERROR: Email template not found"));

// Prepare batches
$batches = (OPERATION_MODE === "bcc") ? array_chunk($recipients, MAX_BCC) : array_chunk($recipients, 1);
$totalBatches = count($batches);

// Start sending process
echo "  \033[1;36mStarting email sending process...\033[0m\n";
echo "  \033[1;34m".str_repeat("-", 100)."\033[0m\n";

foreach ($batches as $index => $batch) {
    $currentNumber = $index + 1;
    $smtpConfig = explode(",", $smtpAccounts[$index % count($smtpAccounts)]);
    
    $mail = new PHPMailer(true);
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = trim($smtpConfig[2] ?? 'smtp.office365.com');
        $mail->Port = (int)($smtpConfig[3] ?? 587);
        $mail->SMTPAuth = true;
        $mail->Username = trim($smtpConfig[0]);
        $mail->Password = trim($smtpConfig[1]);
        $mail->SMTPSecure = ($mail->Port == 587) ? 'tls' : 'ssl';
        
        // Email Content - Using rotating sender email
        $subject = processTemplate($subjectconf[0], $batch[0]);
        $senderName = $fnameconf[0];
        $senderEmail = $fmailconf[$currentEmailIndex % count($fmailconf)]; // Rotate through emails
        
        $mail->setFrom($senderEmail, $senderName);
        $mail->Subject = $subject;
        
        // Process Template
        $templateContent = file_get_contents(TEMPLATE_FILE);
        $processedContent = processTemplate($templateContent, $batch[0]);
        
        $mail->msgHTML($processedContent);
        $mail->AltBody = strip_tags($processedContent);
        
        // Add Custom Headers
        addCustomHeaders($mail, $batch[0]); // Pass recipient email for placeholder replacement
        
        // Add Recipients
        if (OPERATION_MODE === "bcc") {
            $mail->addAddress($batch[0]);
            for ($i = 1; $i < count($batch); $i++) {
                $mail->addBCC($batch[$i]);
            }
        } else {
            $mail->addAddress($batch[0]);
        }
        
        // Send Email
        $mail->send();
        $successCount += count($batch);
        
        // Display with sender email info
        if (OPERATION_MODE === "bcc") {
            $recipientsList = implode(", ", array_slice($batch, 0, 3));
            if (count($batch) > 3) $recipientsList .= ", ...";
            echo "  \033[1;32m✓ [".date("H:i:s")."] [$currentNumber/$totalBatches] SENT bcc to ".count($batch)." recipients (sample: $recipientsList) \033[90m[from: $senderEmail | via: ".maskEmail($mail->Username)."]\033[0m\n";
        } else {
            echo "  \033[1;32m✓ [".date("H:i:s")."] [$currentNumber/$totalBatches] SENT to ".$batch[0]." \033[90m[from: $senderEmail | via: ".maskEmail($mail->Username)."]\033[0m\n";
        }
        
        // Increment for next email
        $currentEmailIndex++;
        
    } catch (Exception $e) {
        $failCount += count($batch);
        if (OPERATION_MODE === "bcc") {
            $recipientsList = implode(", ", array_slice($batch, 0, 3));
            if (count($batch) > 3) $recipientsList .= ", ...";
            echo "  \033[1;31m✗ [".date("H:i:s")."] [$currentNumber/$totalBatches] FAILED bcc to ".count($batch)." recipients (sample: $recipientsList) \033[90m[Error: ".strtok($e->getMessage(), "\n")."]\033[0m\n";
        } else {
            echo "  \033[1;31m✗ [".date("H:i:s")."] [$currentNumber/$totalBatches] FAILED to ".$batch[0]." \033[90m[Error: ".strtok($e->getMessage(), "\n")."]\033[0m\n";
        }
    }
    
    if (DELAY_SECONDS > 0) sleep(DELAY_SECONDS);
}

// Display Final Report
displayStats($successCount, $failCount);
echo "\n  \033[1;35mProcess completed at: ".date('F j, Y H:i:s')."\033[0m\n";