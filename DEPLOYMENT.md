# 🚀 TaskBuddy — Production Deployment Guide

This guide provides step-by-step instructions to deploy **TaskBuddy** on various hosting environments, including Local XAMPP/WAMP, Shared cPanel Hosting, and Linux Cloud VPS (Ubuntu/Debian Apache LAMP).

---

## 📑 Table of Contents
1. [System Requirements](#1-system-requirements)
2. [Local Environment (XAMPP / WAMP / Built-in Server)](#2-local-environment-xampp--wamp--built-in-server)
3. [Shared Hosting (cPanel / Hostinger / Namecheap)](#3-shared-hosting-cpanel--hostinger--namecheap)
4. [Linux VPS Deployment (Ubuntu 22.04 / 24.04 LAMP)](#4-linux-vps-deployment-ubuntu-2204--2404-lamp)
5. [Database Configuration](#5-database-configuration)
6. [SMTP Email Configuration](#6-smtp-email-configuration)
7. [Post-Deployment Verification](#7-post-deployment-verification)

---

## 1. System Requirements

| Component | Minimum | Recommended |
|---|---|---|
| **PHP Version** | PHP 8.0+ | PHP 8.2+ |
| **Database** | MySQL 5.7+ / MariaDB 10.3+ | MariaDB 10.4+ |
| **PHP Extensions** | `pdo_mysql`, `gd`, `mbstring`, `curl`, `openssl` | All standard extensions enabled |
| **Web Server** | Apache 2.4+ (with `mod_rewrite` enabled) | Apache or Nginx with reverse proxy |

---

## 2. Local Environment (XAMPP / WAMP / Built-in Server)

### Method A: One-Click Windows Batch (Fastest)
1. Start **Apache** and **MySQL** in XAMPP Control Panel.
2. Double-click [`start.bat`](start.bat) in the project directory.
3. Access the portal at 👉 **`http://localhost:8000`**

### Method B: Standard PHP Built-in Server
```bash
# In the project root directory:
php -S localhost:8000 router.php
```

### Method C: Standard XAMPP `htdocs`
1. Copy the project folder to `C:\xampp\htdocs\TaskBuddy\`.
2. Import `job_portal.sql` in phpMyAdmin (`http://localhost/phpmyadmin/`).
3. Open `http://localhost/TaskBuddy/` in your browser.

---

## 3. Shared Hosting (cPanel / Hostinger / Namecheap)

1. **Upload Files**:
   - Compress the project root into a `.zip` file (excluding git files).
   - In cPanel **File Manager**, upload and extract the files inside `public_html/` (or your domain directory).

2. **Create MySQL Database**:
   - Go to **MySQL Databases** in cPanel.
   - Create a new database: e.g., `user_job_portal`.
   - Create a new database user with a secure password and assign **ALL PRIVILEGES**.

3. **Import Database Schema**:
   - Open **phpMyAdmin** from cPanel.
   - Select your newly created database.
   - Click **Import** and upload [`job_portal.sql`](job_portal.sql).

4. **Update DB Connection Credentials**:
   - Open `constants/db_config.php`.
   - Update with your live database credentials:
     ```php
     $servername = "localhost";
     $username = "your_cpanel_db_user";
     $password = "your_cpanel_db_password";
     $dbname = "your_cpanel_db_name";
     ```

5. **Ensure Permissions**:
   - Verify `uploads/` and `uploads/avatars/` folders have write permissions (`0755` or `0777`).

---

## 4. Linux VPS Deployment (Ubuntu 22.04 / 24.04 LAMP)

```bash
# 1. Update packages and install LAMP stack
sudo apt update && sudo apt upgrade -y
sudo apt install apache2 mysql-server php8.2 php8.2-mysql php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml unzip git -y

# 2. Enable Apache rewrite module
sudo a2enmod rewrite
sudo systemctl restart apache2

# 3. Clone repository into web root
cd /var/www/
sudo git clone https://github.com/your-username/TaskBuddy.git taskbuddy
sudo chown -R www-data:www-data /var/www/taskbuddy
sudo chmod -R 755 /var/www/taskbuddy
sudo chmod -R 775 /var/www/taskbuddy/uploads

# 4. Configure Apache VirtualHost
sudo nano /etc/apache2/sites-available/taskbuddy.conf
```

Add the following VirtualHost configuration:
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/taskbuddy

    <Directory /var/www/taskbuddy>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/taskbuddy_error.log
    CustomLog ${APACHE_LOG_DIR}/taskbuddy_access.log combined
</VirtualHost>
```

```bash
# 5. Enable site and reload Apache
sudo a2ensite taskbuddy.conf
sudo systemctl reload apache2

# 6. Import database
sudo mysql -u root -p -e "CREATE DATABASE job_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -u root -p job_portal < /var/www/taskbuddy/job_portal.sql

# 7. Install SSL Certificate (Let's Encrypt)
sudo apt install certbot python3-certbot-apache -y
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com
```

---

## 5. Database Configuration

Database connection settings are located in [`constants/db_config.php`](constants/db_config.php):

```php
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "job_portal";

function get_db_connection() {
    global $servername, $username, $password, $dbname;
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    return $conn;
}
?>
```

---

## 6. SMTP Email Configuration

For password reset emails and contact form submissions, update [`constants/settings.php`](constants/settings.php) with your live SMTP details:

```php
$smtp_host = 'smtp.gmail.com';     // or your mail server
$smtp_user = 'your-email@gmail.com';
$smtp_pass = 'your-app-password';
$smtp_port = 587;
```

---

## 7. Post-Deployment Verification

After deploying, perform the following verification steps:
1. Open the home page and verify all CSS styling and images load correctly.
2. Test user registration and login (`/login` & `/register`).
3. Test Task Lister dashboard (`/employer/`) and post a test task.
4. Test Task Seeker dashboard (`/employee/`) and view applicant features.
5. Test Admin panel (`/admin/`) with default credentials (`admin@taskbuddy.com` / `Jahanzaib#1424#`).
6. Run the automated test runner:
   ```bash
   php tests/run_all_tests.php
   ```

---

<p align="center">
  TaskBuddy is deployed and live! 🚀
</p>
