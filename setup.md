# SpecialGig - Full Deployment Guide (cPanel)

Complete step-by-step process to deploy SpecialGig on a cPanel shared hosting server.

---

## Prerequisites

- A cPanel hosting account (PHP 8.0+ recommended)
- A domain name pointed to your hosting
- FTP credentials or cPanel login access
- MySQL database access via phpMyAdmin

---

## Step 1: Create the Database

1. Log in to your **cPanel** dashboard
2. Click **MySQL Databases**
3. Under **Create New Database**, enter a name (e.g., `specialgig_db`) and click **Create Database**
4. Scroll down to **MySQL Users**, create a new user:
   - Username: e.g., `specialgig_user`
   - Password: Generate a strong password and save it
5. Scroll to **Add User to Database**, select the user and database you just created
6. Grant **All Privileges** and click **Make Changes**

### Alternative via phpMyAdmin

1. In cPanel, click **phpMyAdmin**
2. Click the **Databases** tab at the top
3. Enter a database name (e.g., `specialgig_db`) and click **Create**
4. Click the database name on the left sidebar
5. Click the **Import** tab at the top
6. Click **Choose File**, select the `database/schema.sql` file from the project
7. Scroll down and click **Go**
8. Wait for the import to complete (you'll see a success message)

> Your database is now ready with all 30+ tables, default settings, categories, badges, levels, and an admin user.

---

## Step 2: Upload Project Files to cPanel

### Option A: Using File Manager (Smaller Projects)

1. On your local computer, zip the entire project folder (excluding `.git`)
2. In cPanel, click **File Manager**
3. Navigate to `public_html` (or create a subfolder like `specialgig` if you want the site at `yourdomain.com/specialgig`)
4. Click **Upload**, select your zip file
5. After upload, right-click the zip → **Extract**
6. Delete the zip file after extraction

### Option B: Using FTP (Recommended for larger projects)

1. Download and install **FileZilla** (or any FTP client)
2. Get your FTP credentials from cPanel:
   - **FTP Accounts** section in cPanel
   - Create an account or use the main account
3. In FileZilla:
   - **Host**: Your domain or server IP
   - **Username**: Your cPanel FTP username
   - **Password**: Your FTP password
   - **Port**: 21
   - Click **Quickconnect**
4. Navigate to `public_html` on the remote server (right panel)
5. Drag all project files from your local computer (left panel) to `public_html` (right panel)
6. Wait for the transfer to complete

---

## Step 3: Configure Database Connection

1. In cPanel **File Manager**, navigate to your project folder
2. Find and edit `config/database.php`
3. Update with your cPanel database credentials:

```php
<?php
return [
    'driver' => 'mysql',
    'host' => 'localhost',              // Usually "localhost" in cPanel
    'port' => '3306',
    'database' => 'yourdb_specialgig', // Your database name (includes cPanel prefix)
    'username' => 'yourdb_user',       // Your database username
    'password' => 'your_password',     // Your database user's password
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
];
```

> **Note**: In cPanel, database names and usernames typically have a prefix (e.g., `cpaneluser_databasename`)

---

## Step 4: Update Site Configuration

Edit `config/app.php`:

```php
<?php
return [
    'name' => 'SpecialGig',
    'url' => 'https://yourdomain.com',  // Your actual domain with https
    'env' => 'production',               // Change from 'development' to 'production'
    'debug' => false,                    // Set to false in production
    'timezone' => 'UTC',                 // Or your timezone: 'Asia/Dhaka', 'America/New_York'
    // ... rest stays the same
];
```

---

## Step 5: Set Correct File Permissions

In cPanel **File Manager**:

| Path | Permission | Type |
|---|---|---|
| `public/assets/uploads/` | **755** | Directory |
| All PHP files | **644** | Files |
| All directories | **755** | Directories |
| `.htaccess` | **644** | File |

To set permissions:
1. Right-click a file/folder → **Change Permissions**
2. Enter the numeric value (e.g., 755 or 644)
3. Check **"Resursively change permissions"** for directories
4. Click **Change**

---

## Step 6: Configure .htaccess

The project includes a default `.htaccess` in the root. Verify it contains:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

### If installed in a subfolder (e.g., `yourdomain.com/specialgig`):

Replace the root `.htaccess` with:

```apache
RewriteEngine On
RewriteBase /specialgig/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

### Ensure mod_rewrite is enabled:

1. In cPanel, check **Select PHP Version**
2. Click **Switch to PHP Options**
3. Make sure `mod_rewrite` is available (usually enabled by default in cPanel)
4. If you see "Extensions" tab, ensure no rewrite-related extensions are disabled

---

## Step 7: Set PHP Version

1. In cPanel, click **Select PHP Version**
2. Choose **PHP 8.0** or higher from the dropdown
3. Click **Set as current**
4. Ensure these extensions are enabled:
   - `pdo_mysql`
   - `mysqli`
   - `mbstring`
   - `openssl`
   - `json`
   - `fileinfo`

---

## Step 8: Login and Verify

1. Open your browser and navigate to your domain:
   - `https://yourdomain.com` (if installed in `public_html`)
   - `https://yourdomain.com/specialgig` (if installed in subfolder)

2. You should see the **SpecialGig homepage** with the hero section, stats, categories, and featured jobs

3. Login at `https://yourdomain.com/login`:

   | Field | Value |
   |---|---|
   | **Email** | `admin@specialgig.com` |
   | **Password** | `admin123` |

4. After logging in, you'll be redirected to the **Admin Dashboard** at `/admin/dashboard`

5. **Immediately change the admin password**:
   - Go to the admin settings or any settings page
   - Or use phpMyAdmin to update the password hash

---

## Step 9: Post-Deployment Configuration

### 9.1 Update Site Settings

Navigate to `/admin/settings` and configure:

- **General**: Site name, description, logo, favicon, contact email
- **Payment**: Commission rate, min/max withdrawal amounts
- **Referral**: Referral bonus amount, commission rate
- **Security**: reCAPTCHA keys, email verification settings
- **Appearance**: Theme color, footer text

### 9.2 Configure Payment Gateways

Settings are stored in the database. You can update payment gateway configuration:

1. Go to `/admin/settings`
2. Configure the payment group settings
3. For live payment processing, update the payment gateway credentials in the `payment_gateways` table via phpMyAdmin

### 9.3 Set Up Email

Configure email settings in `config/app.php` under the `mail` section:

```php
'mail' => [
    'host' => 'mail.yourdomain.com',     // Your SMTP host
    'port' => 587,
    'username' => 'noreply@yourdomain.com',
    'password' => 'your_email_password',
    'encryption' => 'tls',
    'from_address' => 'noreply@yourdomain.com',
    'from_name' => 'SpecialGig',
],
```

### 9.4 Enable SSL/HTTPS

1. In cPanel, click **SSL/TLS** or **SSL/TLS Status**
2. Run **AutoSSL** (Let's Encrypt) for your domain
3. Once installed, your site will be accessible via `https://`
4. To force HTTPS, add this to your `.htaccess` file:

```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]
```

### 9.5 Set Up Cron Jobs (Optional)

For scheduled tasks, set up cron jobs in cPanel:

1. Click **Cron Jobs** in cPanel
2. Add a new cron job:
   - **Common Settings**: Once per day
   - **Command**: `php /home/username/public_html/cron/daily.php`

(You can create custom cron scripts as needed for cleanup, report generation, etc.)

---

## Step 10: Security Hardening

### Essential Security Steps

- [x] Change default admin password immediately
- [x] Set `debug => false` in `config/app.php`
- [x] Enable HTTPS via AutoSSL
- [x] Set proper file permissions (644/755)

### Recommended Security Steps

- [ ] **Enable reCAPTCHA**: Get free keys from [google.com/recaptcha](https://www.google.com/recaptcha) and configure in admin settings
- [ ] **Set up backups**: Use cPanel's **Backup** or **JetBackup** for daily automated backups
- [ ] **Install firewall**: Use cPanel's **ConfigServer Security & Firewall (CSF)** if available
- [ ] **Disable directory listing**: Add to `.htaccess`:
  ```apache
  Options -Indexes
  ```
- [ ] **Protect sensitive files**: Add to `.htaccess`:
  ```apache
  <FilesMatch "\.(env|sql|md|log)$">
    Order allow,deny
    Deny from all
  </FilesMatch>
  ```

---

## Troubleshooting

### Common Issues and Solutions

| Problem | Cause | Solution |
|---|---|---|
| **Blank white page** | PHP error or DB connection issue | Temporarily set `'debug' => true` in `config/app.php` to see the error. Check DB credentials in `config/database.php` |
| **500 Internal Server Error** | File permissions or .htaccess issue | Reset file permissions (644 files / 755 folders). Check .htaccess syntax |
| **Database connection failed** | Wrong database credentials | Verify database name, username, and password in `config/database.php`. Check that the database user has proper privileges |
| **404 Not Found on pages** | mod_rewrite not enabled or .htaccess missing | Ensure `mod_rewrite` is enabled in Apache. Verify `.htaccess` exists in the root directory |
| **CSS/JS not loading** | Incorrect asset paths | The app uses `/public/assets/...` paths. Ensure the project is in the correct directory relative to the document root |
| **Login not working** | Session or database issue | Check that sessions are working on your server. Verify the admin user exists in the database |
| **"No input file specified"** | PHP-FPM configuration issue | Add to `.htaccess`: `RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]` |
| **File uploads failing** | Upload directory permissions | Set `public/assets/uploads/` to **755** or **777** temporarily |
| **Email not sending** | SMTP configuration | Check mail settings in `config/app.php`. Some cPanel hosts block external SMTP — use the server's local mail server instead |

### Server Requirements Check

If you're unsure if your hosting meets requirements:

| Requirement | Check Method |
|---|---|
| PHP 8.0+ | In cPanel → **Select PHP Version** |
| PDO MySQL | In cPanel → **Select PHP Version** → **Extensions** tab |
| mod_rewrite | In `.htaccess` test with: `RewriteEngine On` — if 500 error, it's not enabled |
| MySQL 5.7+ | In phpMyAdmin → **SQL** tab → run `SELECT VERSION();` |

### Getting Help

If you still have issues:
1. Check your hosting provider's support documentation
2. Contact your hosting provider's support team
3. Enable debug mode temporarily to get detailed error messages

---

## Quick Reference

| URL | Description |
|---|---|
| `https://yourdomain.com` | Homepage |
| `https://yourdomain.com/login` | Login page |
| `https://yourdomain.com/register` | Registration page |
| `https://yourdomain.com/admin/dashboard` | Admin dashboard |
| `https://yourdomain.com/buyer/dashboard` | Buyer dashboard |
| `https://yourdomain.com/worker/dashboard` | Worker dashboard |
| `https://yourdomain.com/jobs/browse` | Browse public jobs |
| `https://yourdomain.com/admin/settings` | Site settings |

### Default Login

| Role | Email | Password |
|---|---|---|
| **Admin** | admin@specialgig.com | admin123 |

> **⚠️ IMPORTANT**: Change the admin password immediately after first login.

---

## Maintenance

### Backup Regularly

Use cPanel's backup features:
- **Full Backup**: cPanel → **Backup** → **Download a Full Account Backup**
- **Database Backup**: phpMyAdmin → Select database → **Export** → **Go**

### Update PHP Version

Periodically check for PHP updates:
1. cPanel → **Select PHP Version**
2. Select the latest stable PHP 8.x version
3. Click **Set as current**

### Monitor Error Logs

1. cPanel → **Error Log** (or **Errors**)
2. Check for recurring errors and address them

---

*Deployment completed. Your SpecialGig marketplace is now live!*
