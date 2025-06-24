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
    'X-Return-Path|##fmail##',
    'X-Relaying-Domain|##domainfmail##',
    'X-Mailer|Amazon WorkMail/1.2.##randnum_100##.##randnum_100##.SES-Client=##generateid##',
    'Feedback-ID|1.eu-west-1.9TFnKlCnR.##generateid##.Dvk8ja+z5xFxabmLHVnERtk=:AMAZONSES',
    'X-Mailer|WebService/1.1.##generateid## YMailNorrin ##rand_ua## ##randnum_100##-SES', 
    'X-Complaints-To|##fmail##',
    'X-MimeOLE|Produced By Microsoft MimeOLE V6.00.##randnum_100##.##randnum_100##.SES-Engine=##generateid##', 
    'X-Proofpoint-Spam-Details|rule=notspam policy=default score=1 suspectscore=0 malwarescore=0 phishscore=0 bulkscore=0 spamscore=1 clxscore=1011 mlxscore=1 mlxlogscore=212 adultscore=0 classifier=spam adjust=0 reason=mlx scancount=1 engine=8.0.1-##rand_low_10##.##rand_low_10##-SES definitions=main-##rand_low_10##.##randnum_100##-SES',
    'X-Auto-Response-Suppress|OOF, AutoReply, RN=notack', 
    'X-Custom-Header|Unique-ID: ##generateid##-##randnum_100##-##generateid##-SES',
    'X-MS-Exchange-Organization-AuthSource|##domainfmail##-##domainfmail##.SES',
    'X-Originating-IP|##rand_ip##',
    'X-Original-AuthSource|##rand_ip##-##domainfmail##-SES',
    'X-MS-Exchange-Organization-AVStamp-Mailbox|##randnum_100##-##randnum_100##-##randnum_100##-SES', 
    'X-Server-Details|Server-Name: ##domainfmail##; Server-IP: ##rand_ip##; Auth-Status: ##generateid##-SES',
    'X-Organization-ID|##generateid##-##generateid##-##randnum_100##-SES', 
    'X-SES-Message-ID|##generateid##-##generateid##',
    'X-SES-Signature|##generateid##-##randnum_100##',
    'X-SES-Signature-Version|1.0-SES-##randnum_100##', 
    'X-SES-Outgoing|##domainfmail##-##randnum_100##-SES',
    'X-SES-Mail-From|##fmail##', 
    'X-SES-Mail-Source|##domainfmail##.##domainfmail##-SES',
    'X-SES-Smtp-Path|##rand_ip##.##domainfmail##-SES', 
    'X-SES-Processed-By|SES-Service=##generateid##; Node=##randnum_100##',
    'X-Email-Class|Marketing-Email; Priority=##domainfmail##-SES',
    'X-YMail-OSG|##generateid##-##randnum_100##-SES',
    'X-YahooFilteredBulk|##rand_ip##',
    'X-Yahoo-Newman-Property|##generateid##-##randnum_100##-SES',
    'X-SES-Feedback-ID|##generateid##.##randnum_100##.##domainfmail##',
    'X-YMail-SEC|##randnum_100##.##generateid##.SES',
    'X-Yahoo-Nostatus|##randnum_100##.##domainfmail##.SES',
    'X-Apparently-To|##fmail## via ##domainfmail##-SES',
    'X-YMail-API-Info|v=2.0; s=2; id=##generateid##; h=##randnum_100##',
    'X-SES-Auth-Token|##generateid##-##randnum_100##',
    'X-Yahoo-SMTP|SMTPSVC/##randnum_100##.##generateid##.SES',
    'X-Yahoo-From|##fmail##-##domainfmail##.SES',
    'X-SES-Header|Type=Notification; Category=Email',
    'X-YMail-Domain|domain=##domainfmail##; ip=##rand_ip##; id=##generateid##',
    'X-Yahoo-Language|en-##randnum_100##',
    'X-YMail-Cache|v=##randnum_100##; h=##rand_ip##.SES',
    'Content-Type: text/html; charset=UTF-8'
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