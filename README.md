-----

# 🔮 Martin Versa Email Sender

**An advanced email delivery system featuring SMTP rotation, email spoofing, and flexible templating.**

Created and maintained by [@martinversa](https://t.me/martinversa)


-----

## 🚀 Features

  * ✅ **Automatic SMTP Rotation:** Distributes email sending across multiple SMTP accounts to enhance deliverability.
  * ✅ **Flexible Delivery Modes:** Supports both `To` and `BCC` delivery methods for diverse sending needs.
  * ✅ **Configurable `MAX_BCC`:** Set the maximum number of BCC recipients per email.
  * ✅ **Spoofed Custom Headers:** Send emails with custom headers for advanced control and branding.
  * ✅ **Smart Templating:** Utilize dynamic placeholders in your email templates for personalized content.
  * ✅ **Intuitive CLI Interface:** A clean and modern command-line interface for easy operation.
  * ✅ **Detailed Logging & Reporting:** Track delivery status and gain insights into your email campaigns.
  * ✅ **Highly Extensible:** Designed for easy customization and integration.

-----

## 📦 Requirements

Before you get started, ensure you have the following installed:

  * **PHP 7.4 or higher**
  * **PHP Extensions:** `openssl`, `mbstring`
  * **[Composer](https://getcomposer.org/)**: For managing PHP dependencies.

-----

## ⚙️ Installation

Follow these steps to get the Martin Versa Email Sender up and running:

### 1\. Clone the Repository

First, clone the project to your local machine:

```bash
git clone https://github.com/your-username/MVC-Sender.git
cd MVC-Sender
```

### 2\. Install Dependencies

Next, install the required PHP libraries using Composer:

```bash
composer require phpmailer/phpmailer
```

-----

## 📁 Configuration

Organize your data in the following folder structure:

```
/MVC-Sender
├── config
│   └── config.php          # Main configuration file
├── data
│   ├── list.txt            # Recipient list
│   └── smtp.txt            # SMTP credentials
├── fm.txt                  # Sender email list
├── includes
│   └── functions.php       # Supporting functions
├── letter
│   └── letter.txt          # Your HTML email template
├── src                     # 
└── send.php                # Main script for sending emails
```

Here's how to format each of your configuration files:

### SMTP Accounts (`data/smtp.txt`)

Each line should contain `username,password,host,port`.

```
smtp_user1,smtp_pass1,smtp.server1.com,587
smtp_user2,smtp_pass2,smtp.server2.com,465
```

### Recipient List (`data/list.txt`)

List one recipient email address per line.

```
recipient1@example.com
recipient2@example.com
```

### Sender Emails (`fm.txt`)

List one sender email address per line.

```
sender1@yourdomain.com
sender2@yourdomain.com
```

### Email Template (`letter/letter.txt`)

You can use HTML for your email template. Utilize the available placeholders for dynamic content.

```html
<!DOCTYPE html>
<html>
<body>
  <h1>Hello ##email##</h1>
  <p>This is a test email sent on ##date## at ##time##</p>
  <p>Your unique ID: ##random##</p>
  <a href="##shortlink##">Click here</a>
</body>
</html>
```

-----

## ✨ Template Placeholders

Enhance your email content with these dynamic placeholders:

| Placeholder     | Description                      |
| :-------------- | :------------------------------- |
| `##random##`    | A unique, randomly generated string. |
| `##email##`     | The recipient's email address.   |
| `##date##`      | The current date.                |
| `##time##`      | The current time.                |
| `##shortlink##` | A shortened tracking or redirect URL. |

-----

## 🚀 Usage

### Run the Sender

Execute the `send.php` script to start sending emails:

```bash
php send.php
```

### Edit Configuration

Adjust the core sending parameters by editing `config.php`:

| Parameter       | Description                                  |
| :-------------- | :------------------------------------------- |
| `OPERATION_MODE`| Set to `to` for direct sending or `bcc` for blind carbon copy. |
| `MAX_BCC`       | Maximum number of BCC recipients per email when in `bcc` mode. |
| `DELAY_SECONDS` | Delay (in seconds) between each email send.  |

-----
