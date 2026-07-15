-- SpecialGig Database Schema

CREATE DATABASE IF NOT EXISTS specialgig CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE specialgig;

-- Users table
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'buyer', 'worker') NOT NULL DEFAULT 'buyer',
    status ENUM('active', 'suspended', 'banned') NOT NULL DEFAULT 'active',
    email_verified_at TIMESTAMP NULL,
    kyc_status ENUM('none', 'pending', 'verified', 'rejected') NOT NULL DEFAULT 'none',
    two_factor_enabled TINYINT(1) NOT NULL DEFAULT 0,
    two_factor_secret VARCHAR(255) NULL,
    remember_token VARCHAR(255) NULL,
    referral_code VARCHAR(20) NULL UNIQUE,
    referred_by BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_role (role),
    INDEX idx_status (status),
    INDEX idx_referral_code (referral_code),
    FOREIGN KEY (referred_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- User profiles
CREATE TABLE user_profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    full_name VARCHAR(100) NULL,
    phone VARCHAR(30) NULL,
    avatar VARCHAR(255) NULL,
    bio TEXT NULL,
    country VARCHAR(100) NULL,
    city VARCHAR(100) NULL,
    address TEXT NULL,
    timezone VARCHAR(50) DEFAULT 'UTC',
    language VARCHAR(10) DEFAULT 'en',
    date_of_birth DATE NULL,
    gender ENUM('male', 'female', 'other') NULL,
    website VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- User skills
CREATE TABLE user_skills (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    skill VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- User social links
CREATE TABLE user_social_links (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    platform VARCHAR(50) NOT NULL,
    url VARCHAR(255) NOT NULL,
    verified TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Categories
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(255) NULL,
    description TEXT NULL,
    parent_id BIGINT UNSIGNED NULL,
    order_column INT NOT NULL DEFAULT 0,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Jobs
CREATE TABLE jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    instructions TEXT NULL,
    proof_requirements TEXT NULL,
    reward DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    available_slots INT NOT NULL DEFAULT 1,
    filled_slots INT NOT NULL DEFAULT 0,
    total_budget DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    country_restriction VARCHAR(100) NULL,
    device_restriction VARCHAR(50) NULL,
    browser_restriction VARCHAR(50) NULL,
    completion_time_limit INT NULL COMMENT 'In hours',
    approval_time_limit INT NULL COMMENT 'In hours',
    is_manual_approval TINYINT(1) NOT NULL DEFAULT 1,
    is_hidden TINYINT(1) NOT NULL DEFAULT 0,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    is_urgent TINYINT(1) NOT NULL DEFAULT 0,
    difficulty ENUM('beginner', 'intermediate', 'advanced') NOT NULL DEFAULT 'beginner',
    status ENUM('pending', 'active', 'paused', 'completed', 'cancelled', 'rejected') NOT NULL DEFAULT 'pending',
    rejection_reason TEXT NULL,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category_id),
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_featured (is_featured),
    INDEX idx_created (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Job files
CREATE TABLE job_files (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_id BIGINT UNSIGNED NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Job applications
CREATE TABLE job_applications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_id BIGINT UNSIGNED NOT NULL,
    worker_id BIGINT UNSIGNED NOT NULL,
    status ENUM('accepted', 'submitted', 'approved', 'rejected', 'cancelled') NOT NULL DEFAULT 'accepted',
    proof TEXT NULL,
    proof_files TEXT NULL,
    worker_notes TEXT NULL,
    buyer_notes TEXT NULL,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_job (job_id),
    INDEX idx_worker (worker_id),
    INDEX idx_status (status),
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (worker_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Wallets
CREATE TABLE wallets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    balance DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    pending_balance DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    referral_earnings DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    bonus_earnings DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    total_deposited DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    total_withdrawn DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    total_earned DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Transactions
CREATE TABLE transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    wallet_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    type ENUM('deposit', 'withdrawal', 'payment', 'refund', 'commission', 'referral', 'bonus') NOT NULL,
    amount DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    fee DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    balance_before DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    balance_after DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    status ENUM('pending', 'completed', 'failed', 'cancelled') NOT NULL DEFAULT 'pending',
    description TEXT NULL,
    reference VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_wallet (wallet_id),
    INDEX idx_user (user_id),
    INDEX idx_type (type),
    INDEX idx_status (status),
    FOREIGN KEY (wallet_id) REFERENCES wallets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Deposits
CREATE TABLE deposits (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    wallet_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    fee DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    payment_method ENUM('bkash','nagad','rocket','bank_transfer','paypal','stripe','crypto_usdt','manual') NOT NULL,
    status ENUM('pending', 'completed', 'failed', 'cancelled') NOT NULL DEFAULT 'pending',
    transaction_id VARCHAR(100) NULL,
    reference VARCHAR(100) NULL,
    admin_note TEXT NULL,
    approved_by BIGINT UNSIGNED NULL,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (wallet_id) REFERENCES wallets(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Withdrawals
CREATE TABLE withdrawals (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    wallet_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(14,2) NOT NULL DEFAULT 0.00,
    fee DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    payment_method ENUM('bkash','nagad','rocket','bank_transfer','paypal','stripe','crypto_usdt','manual') NOT NULL,
    payment_details TEXT NULL,
    status ENUM('pending', 'completed', 'failed', 'cancelled') NOT NULL DEFAULT 'pending',
    transaction_id VARCHAR(100) NULL,
    admin_note TEXT NULL,
    approved_by BIGINT UNSIGNED NULL,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (wallet_id) REFERENCES wallets(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Notifications
CREATE TABLE notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    type ENUM('info', 'success', 'warning', 'error') NOT NULL DEFAULT 'info',
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    link VARCHAR(255) NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_read (is_read),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Reviews
CREATE TABLE reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    from_user_id BIGINT UNSIGNED NOT NULL,
    to_user_id BIGINT UNSIGNED NOT NULL,
    job_id BIGINT UNSIGNED NOT NULL,
    application_id BIGINT UNSIGNED NULL,
    rating TINYINT UNSIGNED NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_from (from_user_id),
    INDEX idx_to (to_user_id),
    INDEX idx_job (job_id),
    FOREIGN KEY (from_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (to_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Support tickets
CREATE TABLE support_tickets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    priority ENUM('low', 'medium', 'high', 'urgent') NOT NULL DEFAULT 'medium',
    status ENUM('open', 'in_progress', 'waiting', 'resolved', 'closed') NOT NULL DEFAULT 'open',
    assigned_to BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Ticket replies
CREATE TABLE ticket_replies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES support_tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Referrals
CREATE TABLE referrals (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    referrer_id BIGINT UNSIGNED NOT NULL,
    referred_id BIGINT UNSIGNED NOT NULL UNIQUE,
    reward DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    status ENUM('pending', 'completed', 'cancelled') NOT NULL DEFAULT 'completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_referrer (referrer_id),
    FOREIGN KEY (referrer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (referred_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Badges
CREATE TABLE badges (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(255) NULL,
    description TEXT NULL,
    criteria TEXT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- User badges
CREATE TABLE user_badges (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    badge_id BIGINT UNSIGNED NOT NULL,
    earned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_badge (user_id, badge_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (badge_id) REFERENCES badges(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Levels
CREATE TABLE levels (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    min_earnings DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    max_earnings DECIMAL(12,2) NULL,
    benefits TEXT NULL,
    icon VARCHAR(255) NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Coupons
CREATE TABLE coupons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    discount_type ENUM('percentage', 'fixed') NOT NULL DEFAULT 'percentage',
    discount_value DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    min_amount DECIMAL(12,2) NULL,
    max_uses INT NOT NULL DEFAULT 0,
    used_count INT NOT NULL DEFAULT 0,
    expires_at TIMESTAMP NULL,
    status ENUM('active', 'expired', 'disabled') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- CMS Pages
CREATE TABLE cms_pages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NULL,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    status ENUM('published', 'draft') NOT NULL DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Settings
CREATE TABLE settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    `value` LONGTEXT NULL,
    type ENUM('text', 'number', 'boolean', 'json', 'image') NOT NULL DEFAULT 'text',
    group_name VARCHAR(50) NOT NULL DEFAULT 'general',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Payment gateways
CREATE TABLE payment_gateways (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE,
    config JSON NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 0,
    order_column INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Disputes
CREATE TABLE disputes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_id BIGINT UNSIGNED NOT NULL,
    application_id BIGINT UNSIGNED NULL,
    buyer_id BIGINT UNSIGNED NOT NULL,
    worker_id BIGINT UNSIGNED NOT NULL,
    reason TEXT NOT NULL,
    evidence TEXT NULL,
    status ENUM('open', 'under_review', 'resolved', 'closed') NOT NULL DEFAULT 'open',
    resolution TEXT NULL,
    resolved_by BIGINT UNSIGNED NULL,
    resolved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_job (job_id),
    INDEX idx_status (status),
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (worker_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (resolved_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Activity logs
CREATE TABLE activity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_created (created_at)
) ENGINE=InnoDB;

-- Login history
CREATE TABLE login_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    status ENUM('success', 'failed') NOT NULL DEFAULT 'success',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- FAQs
CREATE TABLE faqs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    category VARCHAR(100) NULL,
    order_column INT NOT NULL DEFAULT 0,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Contacts / inquiries
CREATE TABLE contacts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Newsletter subscribers
CREATE TABLE newsletters (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Announcements
CREATE TABLE announcements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'error') NOT NULL DEFAULT 'info',
    starts_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Insert default data
INSERT INTO `settings` (`key`, `value`, `type`, `group_name`) VALUES
('site_name', 'SpecialGig', 'text', 'general'),
('site_description', 'Premium Micro Job Marketplace', 'text', 'general'),
('site_logo', '', 'image', 'general'),
('site_favicon', '', 'image', 'general'),
('site_email', 'support@specialgig.com', 'text', 'general'),
('site_phone', '', 'text', 'general'),
('site_address', '', 'text', 'general'),
('commission_rate', '10', 'number', 'payment'),
('min_withdrawal', '5', 'number', 'payment'),
('max_withdrawal', '10000', 'number', 'payment'),
('referral_bonus', '1', 'number', 'referral'),
('referral_commission_rate', '5', 'number', 'referral'),
('new_user_bonus', '0', 'number', 'payment'),
('daily_reward', '0', 'number', 'reward'),
('maintenance_mode', '0', 'boolean', 'general'),
('registration_enabled', '1', 'boolean', 'general'),
('kyc_required', '0', 'boolean', 'security'),
('email_verification_required', '1', 'boolean', 'security'),
('recaptcha_enabled', '0', 'boolean', 'security'),
('theme_color', '#6366f1', 'text', 'appearance'),
('footer_text', '© 2026 SpecialGig. All rights reserved.', 'text', 'general');

INSERT INTO `payment_gateways` (`name`, `slug`, `is_active`, `order_column`) VALUES
('bKash', 'bkash', 0, 1),
('Nagad', 'nagad', 0, 2),
('Rocket', 'rocket', 0, 3),
('Bank Transfer', 'bank_transfer', 0, 4),
('PayPal', 'paypal', 1, 5),
('Stripe', 'stripe', 1, 6),
('Cryptocurrency (USDT)', 'crypto_usdt', 0, 7),
('Manual Payment', 'manual', 1, 8);

INSERT INTO `levels` (`name`, `slug`, `min_earnings`, `benefits`) VALUES
('Bronze', 'bronze', 0, 'Basic access to platform features'),
('Silver', 'silver', 100, 'Lower commission rates, priority support'),
('Gold', 'gold', 500, 'Even lower commissions, featured profile'),
('Platinum', 'platinum', 2000, 'Minimal commissions, badge, priority withdrawals'),
('Diamond', 'diamond', 5000, 'Zero commission, exclusive jobs, VIP support');

INSERT INTO `badges` (`name`, `slug`, `description`) VALUES
('Rising Star', 'rising-star', 'Complete your first 10 jobs'),
('Top Worker', 'top-worker', 'Top 10% of workers by earnings'),
('Trusted Buyer', 'trusted-buyer', 'Posted 20+ jobs with high ratings'),
('Referral King', 'referral-king', 'Refer 50+ active users'),
('Early Adopter', 'early-adopter', 'Joined within the first month'),
('100 Club', '100-club', 'Complete 100 jobs'),
('Perfect Rating', 'perfect-rating', 'Maintain 5.0 rating for 30 days'),
('Big Spender', 'big-spender', 'Spent over $1,000 on jobs');

INSERT INTO `categories` (`name`, `slug`, `icon`, `description`) VALUES
('Facebook', 'facebook', 'facebook', 'Facebook related tasks'),
('YouTube', 'youtube', 'youtube', 'YouTube related tasks'),
('Instagram', 'instagram', 'instagram', 'Instagram related tasks'),
('TikTok', 'tiktok', 'video', 'TikTok related tasks'),
('Telegram', 'telegram', 'send', 'Telegram related tasks'),
('Twitter/X', 'twitter', 'twitter', 'Twitter/X related tasks'),
('Website Visit', 'website-visit', 'globe', 'Website visit tasks'),
('App Install', 'app-install', 'download', 'App installation tasks'),
('Survey', 'survey', 'clipboard-list', 'Survey completion tasks'),
('Signup', 'signup', 'user-plus', 'Signup tasks'),
('Review', 'review', 'star', 'Review and rating tasks'),
('SEO', 'seo', 'search', 'SEO related tasks'),
('AI Tasks', 'ai-tasks', 'cpu', 'AI related tasks'),
('Other Tasks', 'other-tasks', 'more-horizontal', 'Other miscellaneous tasks');

INSERT INTO `cms_pages` (`slug`, `title`, `content`, `status`) VALUES
('about', 'About Us', '<h2>Welcome to SpecialGig</h2><p>SpecialGig is a premium micro job marketplace connecting buyers and workers worldwide.</p>', 'draft'),
('privacy-policy', 'Privacy Policy', '<h2>Privacy Policy</h2><p>Your privacy is important to us.</p>', 'draft'),
('terms-of-service', 'Terms of Service', '<h2>Terms of Service</h2><p>Please read these terms carefully.</p>', 'draft'),
('refund-policy', 'Refund Policy', '<h2>Refund Policy</h2><p>Our refund policy details.</p>', 'draft');

-- Create admin user (password: admin123)
INSERT INTO `users` (`username`, `email`, `password`, `role`, `status`, `email_verified_at`) VALUES
('admin', 'admin@specialgig.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', NOW());

INSERT INTO `user_profiles` (`user_id`, `full_name`) VALUES (1, 'Administrator');

INSERT INTO `wallets` (`user_id`, `balance`) VALUES (1, 0);
