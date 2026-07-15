# SpecialGig - Simple Deployment Guide

Two steps to go live.

---

## Step 1: Import Database

1. Open **phpMyAdmin**
2. Click **Import** tab
3. Select `database/schema.sql` from this project
4. Click **Go**

That's it. All tables, settings, and default data will be created.

---

## Step 2: Configure `.env`

Copy `.env.example` to `.env` and edit these values:

```
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
APP_URL=https://yourdomain.com
```

> **Note**: In cPanel, database names and usernames usually have a prefix (e.g., `cpaneluser_databasename`).

---

Upload all project files to your server (via FTP or cPanel File Manager).

**Your site is live.**

---

## Default Login

| Role | Email | Password |
|---|---|---|
| **Admin** | admin@specialgig.com | admin123 |

> Change the admin password after first login.

---

## Requirements

- PHP 8.0+
- MySQL 5.7+
- PDO MySQL extension enabled
- Apache mod_rewrite enabled
