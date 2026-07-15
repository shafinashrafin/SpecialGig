# SpecialGig - Premium Micro Job Marketplace

A complete micro job marketplace built with PHP. Buyers create jobs, workers complete tasks for rewards, and administrators manage the entire ecosystem.

## Features

- **Multi-Role System**: Admin, Buyer, Worker dashboards
- **Job Management**: Create, approve, manage jobs with full workflow
- **Wallet System**: Deposit, withdraw, transaction history
- **Payment Methods**: PayPal, Stripe, bKash, Nagad, Rocket, Crypto, Bank Transfer
- **Referral Program**: Invite friends, earn commissions
- **Rating & Review**: 5-star rating system
- **Support Tickets**: Customer support system
- **Admin Panel**: Complete administrative control
- **CMS**: Manage pages, FAQs, announcements
- **Modern UI**: Responsive, dark admin theme, glassmorphism design

## Requirements

- PHP 8.0+
- MySQL/MariaDB 5.7+
- Apache with mod_rewrite or Nginx
- Composer (optional, for future packages)

## Installation

1. **Clone the repository**
```bash
git clone https://github.com/yourusername/specialgig.git
cd specialgig
```

2. **Create the database**
```bash
mysql -u root -p < database/schema.sql
```

3. **Configure database**
Edit `config/database.php` with your database credentials.

4. **Configure app settings**
Edit `config/app.php` with your site URL and other settings.

5. **Web server setup**

### Apache (.htaccess already configured)
Ensure `mod_rewrite` is enabled:
```bash
sudo a2enmod rewrite
```

### Nginx
```nginx
server {
    listen 80;
    server_name specialgig.local;
    root /path/to/specialgig;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?url=$uri&$args;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location /public/ {
        try_files $uri =404;
    }
}
```

6. **Set permissions**
```bash
chmod -R 755 public/assets/uploads
chmod -R 755 storage
```

7. **Access the application**
- Site: `http://localhost:8080`
- Admin: `http://localhost:8080/admin/dashboard`
- Login: `admin@specialgig.com` / `admin123`

## Default Admin Account

- **Email**: admin@specialgig.com
- **Password**: admin123

## Directory Structure

```
├── app/
│   ├── controllers/       # Application controllers
│   │   └── admin/         # Admin panel controllers
│   ├── core/              # MVC framework core
│   ├── helpers/           # Helper functions
│   ├── models/            # Database models
│   └── views/             # View templates
│       ├── admin/         # Admin panel views
│       ├── auth/          # Login/Register views
│       ├── buyer/         # Buyer dashboard views
│       ├── worker/        # Worker dashboard views
│       ├── homepage/      # Public page views
│       ├── jobs/          # Job listing/detail views
│       └── layouts/       # Layout templates
├── config/                # Configuration files
├── database/              # Database schema
├── public/                # Public assets
│   └── assets/
│       ├── css/
│       ├── js/
│       └── uploads/
└── index.php              # Application entry point
```

## Architecture

The application uses a custom MVC framework:
- **Router**: `App.php` - URL parsing and controller dispatch
- **Controllers**: Handle request logic
- **Models**: Database interaction via PDO
- **Views**: PHP template files with extracted data
- **Database**: Singleton PDO connection with query builder methods

## Security

- Password hashing with bcrypt
- CSRF protection
- Input validation and sanitization
- Prepared statements (PDO)
- Session security
- Role-based access control

## License

MIT License
