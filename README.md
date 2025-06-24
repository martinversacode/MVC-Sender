# 🔮 Martin Versa Email Sender

**Advanced email delivery system with SMTP rotation, spoofing, and flexible control.**  
Created and maintained by [@martinversa](https://t.me/martinversa)

![Terminal Preview](https://i.imgur.com/example-image.png)  
<sub>📸 Replace this with an actual screenshot from your terminal</sub>

---

## 📦 Requirements

- PHP 7.4 or higher
- PHP Extensions: `openssl`, `mbstring`
- [Composer](https://getcomposer.org/) (for PHPMailer dependency)

---

## 🛠️ Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/your-username/martin-versa-email-sender.git
   cd martin-versa-email-sender
Install dependencies:

bash
Salin
Edit
composer require phpmailer/phpmailer
Folder structure:

bash
Salin
Edit
/martin-versa-email-sender
├── /data
│   ├── list.txt       # Recipient list
│   └── smtp.txt       # SMTP credentials
├── /letter
│   └── letter.txt     # Email HTML template
└── fm.txt             # Sender email list
⚙️ Configuration
📄 data/smtp.txt
Salin
Edit
smtp_user1,smtp_pass1,smtp.server1.com,587
smtp_user2,smtp_pass2,smtp.server2.com,465
📄 data/list.txt
graphql
Salin
Edit
recipient1@example.com
recipient2@example.com
📄 fm.txt
graphql
Salin
Edit
sender1@yourdomain.com
sender2@yourdomain.com
📄 letter/letter.txt
html
Salin
Edit
<!DOCTYPE html>
<html>
<body>
  <h1>Hello ##email##</h1>
  <p>This is a test email sent on ##date## at ##time##</p>
  <p>Your unique ID: ##random##</p>
  <a href="##shortlink##">Click here</a>
</body>
</html>
🚀 Usage
Run the sender:

bash
Salin
Edit
php send.php
Edit main config values in config.php:

Parameter	Description
OPERATION_MODE	'to' or 'bcc'
MAX_BCC	Maximum BCC recipients per email
DELAY_SECONDS	Delay between sends (in seconds)

🌟 Features
✅ Automatic SMTP rotation

✅ Support for To and BCC delivery modes

✅ Spoofed custom headers

✅ Templating with smart placeholders

✅ Futuristic CLI interface

✅ Delivery logging and reporting

✅ Easy to extend and configure

✨ Template Placeholders
Placeholder	Description
##random##	Random string (unique ID)
##email##	Recipient's email address
##date##	Current date
##time##	Current time
##shortlink##	Shortened tracking or redirect URL
