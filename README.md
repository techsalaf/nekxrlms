# NekXR LMS - Learning Management System

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-purple.svg)](https://php.net)

A comprehensive, feature-rich Learning Management System built with Laravel 11. NekXR LMS (TutoLab) is a personal course selling platform that enables instructors to create, manage, and monetize their educational content with ease.

## 🚀 Features

### Core Functionality
- **Course Management**
  - Multi-section course structure
  - Video lessons with chunk upload support
  - Rich text content with HTML editor
  - Course categories and subcategories
  - Course reviews and ratings
  - Coupon system for discounts

- **User Management**
  - Student registration and authentication
  - Social login (Google, Facebook, LinkedIn)
  - User profiles and progress tracking
  - Purchase history and certificates
  - Two-factor authentication (2FA)

- **Admin Panel**
  - Comprehensive dashboard with analytics
  - User and course management
  - Payment gateway configuration
  - Email/SMS/Push notification templates
  - Extension management
  - System optimization tools

### Payment Integration
Supports 30+ payment gateways including:
- **Cards**: Stripe, Authorize.net, Checkout, NMI, Flutterwave
- **Digital Wallets**: PayPal, Razorpay, Paystack, Mollie
- **Crypto**: BTCPay, Coingate, Binance, Blockchain
- **Regional**: Paytm, SSL Commerz, Aamarpay, Instamojo
- **Others**: Skrill, Payeer, Perfect Money, 2Checkout

### Communication
- **Email**: Multiple SMTP providers, SendGrid, Mailgun, Mailjet
- **SMS**: Twilio, Vonage, MessageBird, Textmagic
- **Push Notifications**: Firebase Cloud Messaging (FCM)

### Additional Features
- **Frontend**
  - Responsive Bootstrap-based design
  - Multiple template support
  - Page builder for custom pages
  - SEO optimization tools
  - Cookie consent management

- **Extensions**
  - Google Analytics integration
  - reCAPTCHA v2/v3 support
  - Custom CAPTCHA
  - Tawk.to live chat
  - Facebook Pixel

- **Developer Tools**
  - RESTful API structure
  - Form generator
  - File manager
  - Multi-language support
  - Maintenance mode

## 📋 Requirements

- **PHP** >= 8.3
- **MySQL** >= 5.7 or **MariaDB** >= 10.3
- **Composer** >= 2.x
- **Node.js** >= 16.x (for asset compilation)

### PHP Extensions
- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON
- BCMath
- GD or Imagick
- Fileinfo

### Server Requirements
- Apache 2.4+ or Nginx 1.18+
- mod_rewrite enabled (Apache)
- Write permissions for storage/ and bootstrap/cache/

## 🛠️ Installation

### 1. Clone the Repository
```bash
git clone https://github.com/techsalaf/nekxrlms.git
cd nekxrlms
```

### 2. Install Dependencies
```bash
cd core
composer install
npm install && npm run build
```

### 3. Environment Configuration
```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
Configure your database credentials in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nekxrlms
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Run migrations:
```bash
php artisan migrate --seed
```

### 5. Storage Link
```bash
php artisan storage:link
```

### 6. Set Permissions
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 7. Configure Web Server

#### Apache
Ensure the document root points to the `public` directory and `.htaccess` is enabled.

#### Nginx
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/nekxrlms/core/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## ⚙️ Configuration

### Admin Access
After installation, access the admin panel:
- URL: `https://yourdomain.com/admin`
- Default credentials are set during seeding (check seeders)

### Essential Settings

#### 1. General Settings
Navigate to **Admin > Settings > General Settings**
- Site name and description
- Timezone and currency
- Registration settings
- Email verification options

#### 2. Payment Gateways
Navigate to **Admin > Payment Settings**
- Enable/disable gateways
- Configure API credentials
- Set transaction fees

#### 3. Email Configuration
Navigate to **Admin > Notification Settings > Email**
- SMTP settings or email service API keys
- Email templates customization
- Test email functionality

#### 4. Social Login
Create OAuth apps and configure in **Admin > Settings > Social Credentials**
- Google OAuth 2.0
- Facebook App
- LinkedIn App

#### 5. Firebase Push Notifications
Navigate to **Admin > Notification Settings > Push Notification**
- Upload Firebase service account JSON
- Configure FCM settings

### Environment Variables

Key `.env` configurations:

```env
# Application
APP_NAME="NekXR LMS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nekxrlms
DB_USERNAME=root
DB_PASSWORD=

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Social Login (optional)
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URL=

FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
FACEBOOK_REDIRECT_URL=

LINKEDIN_CLIENT_ID=
LINKEDIN_CLIENT_SECRET=
LINKEDIN_REDIRECT_URL=
```

## 🎯 Usage

### For Administrators

1. **Add Courses**
   - Admin > Courses > Add New
   - Fill in course details, pricing, and description
   - Upload course thumbnail
   - Add sections and lessons

2. **Manage Users**
   - Admin > Users
   - View user details, purchases, and activity
   - Send notifications
   - Manage permissions

3. **Configure Payments**
   - Admin > Payment Settings
   - Enable payment gateways
   - Set conversion rates and fees

### For Students

1. **Browse Courses**
   - Explore courses by category
   - Read reviews and ratings
   - Preview course content

2. **Purchase Courses**
   - Add courses to cart
   - Apply coupon codes
   - Complete payment via preferred gateway

3. **Learn**
   - Access purchased courses
   - Watch video lessons
   - Track progress
   - Submit reviews

## 📁 Project Structure

```
nekxrlms/
├── assets/              # Public assets (images, CSS, JS)
│   ├── admin/          # Admin panel assets
│   ├── templates/      # Frontend theme assets
│   └── global/         # Shared resources
├── core/               # Laravel application
│   ├── app/           # Application logic
│   │   ├── Http/      # Controllers, middleware
│   │   ├── Models/    # Eloquent models
│   │   ├── Lib/       # Custom libraries
│   │   └── Notify/    # Notification handlers
│   ├── config/        # Configuration files
│   ├── database/      # Migrations and seeders
│   ├── resources/     # Views and language files
│   ├── routes/        # Route definitions
│   └── storage/       # Generated files, logs
├── docs/              # Documentation site
├── index.php          # Application entry point
└── .gitignore         # Git ignore rules
```

## 🔒 Security

### Best Practices Implemented
- CSRF protection on all forms
- XSS prevention with input sanitization
- SQL injection protection via Eloquent ORM
- Secure password hashing (bcrypt)
- Two-factor authentication support
- Rate limiting on API endpoints
- Secure file upload validation

### Security Checklist
- [ ] Change default admin credentials immediately
- [ ] Set `APP_DEBUG=false` in production
- [ ] Use HTTPS with valid SSL certificate
- [ ] Configure firewall rules
- [ ] Regular database backups
- [ ] Keep dependencies updated
- [ ] Monitor application logs
- [ ] Implement IP whitelisting for admin panel (recommended)

## 🔧 Maintenance

### Cache Management
```bash
# Clear application cache
php artisan cache:clear

# Clear configuration cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear

# Optimize for production
php artisan optimize
```

### Database Backup
```bash
# Using mysqldump
mysqldump -u username -p nekxrlms > backup_$(date +%Y%m%d).sql

# Restore from backup
mysql -u username -p nekxrlms < backup_20260108.sql
```

### Update System
```bash
git pull origin main
composer install --no-dev
php artisan migrate
php artisan optimize
```

## 🌐 Multi-Language Support

Add new languages:
1. Navigate to **Admin > Language Settings**
2. Click "Add New Language"
3. Translate JSON language file
4. Enable language for frontend

## 📊 Performance Optimization

### Recommended Optimizations
- Enable OPcache for PHP
- Use Redis or Memcached for caching
- Configure queue workers for background jobs
- Optimize images before upload
- Use CDN for static assets
- Enable Gzip compression

### Queue Configuration
```bash
# Run queue worker
php artisan queue:work

# Run as supervisor (production)
# Create /etc/supervisor/conf.d/laravel-worker.conf
```

## 🐛 Troubleshooting

### Common Issues

**Issue**: White screen after installation
- **Solution**: Check file permissions on `storage/` and `bootstrap/cache/`

**Issue**: Payment gateway not working
- **Solution**: Verify API credentials and callback URLs

**Issue**: Emails not sending
- **Solution**: Test SMTP settings via Admin > Email Settings > Test Mail

**Issue**: Video upload fails
- **Solution**: Increase `upload_max_filesize` and `post_max_size` in php.ini

### Debug Mode
Enable debug mode temporarily in `.env`:
```env
APP_DEBUG=true
```
**Note**: Never enable debug mode in production!

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Coding Standards
- Follow PSR-12 coding style
- Write descriptive commit messages
- Add comments for complex logic
- Update documentation for new features

## 📞 Support

For support and questions:
- **Issues**: [GitHub Issues](https://github.com/techsalaf/nekxrlms/issues)
- **Discussions**: [GitHub Discussions](https://github.com/techsalaf/nekxrlms/discussions)
- **Documentation**: `/docs` folder or hosted documentation

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com)
- UI powered by [Bootstrap](https://getbootstrap.com)
- Icons by [Font Awesome](https://fontawesome.com) and [Line Awesome](https://icons8.com/line-awesome)
- Payment integrations via official SDKs
- Firebase Cloud Messaging for push notifications

## 📈 Roadmap

- [ ] Mobile application (React Native)
- [ ] Live streaming classes
- [ ] Assignment and quiz system
- [ ] Certificate generation
- [ ] Affiliate program
- [ ] Gamification features
- [ ] Advanced analytics dashboard
- [ ] API documentation
- [ ] Docker containerization

---

**Version**: 1.0.0  
**Release Date**: January 8, 2026  
**Maintained by**: TechSalaf

Made with ❤️ for educators and learners worldwide.
