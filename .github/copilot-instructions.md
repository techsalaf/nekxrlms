# NekXR LMS Development Guide

## Project Overview
NekXR LMS (TutoLab) is a Laravel 11-based Learning Management System for course creation, monetization, and student management. Built with PHP 8.3+ and supports 30+ payment gateways.

## Architecture

### Directory Structure
- **Root**: `index.php` bootstraps Laravel from `/core` directory
- **Core**: `/core` contains the Laravel 11 application (not typical Laravel root structure)
- **Assets**: `/assets` stores public assets (admin, templates, uploads) outside core/public
- **Routes**: Segmented by role - `admin.php`, `user.php`, `web.php`, `ipn.php`

### Key Components
- **Courses**: Multi-section structure with video lessons, reviews, coupons
- **Payment**: Gateway controllers in `core/app/Http/Controllers/Gateway/[GatewayName]/`
- **Notifications**: Multi-channel system (Email, SMS, Push) in `core/app/Notify/`
- **Templates**: Frontend templates in `assets/templates/basic/` and views in `core/resources/views/templates/`

## Critical Conventions

### Helper Functions (core/app/Http/Helpers/helpers.php)
Auto-loaded via composer. Essential helpers:
```php
gs('key')                    // Get GeneralSetting (cached), e.g., gs('site_name')
notify($user, 'template')    // Send multi-channel notifications
fileUploader($file, $path)   // Handle file uploads with size/thumbnail generation
showAmount($amount)          // Format currency per settings
activeTemplate()             // Get current template path (e.g., 'templates.basic.')
getFilePath('category')      // Get configured file storage paths
```

### Status Constants (core/app/Constants/Status.php)
Use constants instead of magic numbers:
```php
Status::ENABLE / Status::DISABLE
Status::YES / Status::NO
Status::PAYMENT_SUCCESS / Status::PAYMENT_PENDING / Status::PAYMENT_REJECT
Status::USER_ACTIVE / Status::USER_BAN
```

### Response Pattern
Controllers use `$notify` array with `withNotify()`:
```php
$notify[] = ['success', 'Action completed'];
$notify[] = ['error', 'Something went wrong'];
return back()->withNotify($notify);
```

### Model Traits
- **GlobalStatus**: Adds `status()`, `changeStatus()` methods to models
- **UserNotify**: Notification methods for User model
- **Searchable**: Trait in `core/app/Lib/Searchable.php` for query filtering

## Development Workflows

### Installation & Setup
```bash
cd core
composer install
npm install && npm run build
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

### Asset Compilation
Frontend assets use Vite (not Mix):
```bash
cd core
npm run dev    # Development with hot reload
npm run build  # Production build
```

### Running Application
```bash
cd core
php artisan serve
```
Or configure Apache/Nginx to point to `/core/public` (see README.md for configs)

### Key Artisan Commands
```bash
php artisan optimize         # Cache config, routes, views
php artisan cache:clear      # Clear application cache
php artisan config:clear     # Clear config cache
```

## Database Patterns

### Relationships
- Course → hasMany → Section → hasMany → Lesson
- Course → hasMany → CoursePurchase (join with User)
- User → hasMany → CoursePurchase, Review, SupportTicket
- Category → hasMany → Course

### Query Scopes
Models use local scopes:
```php
Course::active()   // Status::ENABLE + active category
Course::free()     // premium = Status::NO
Course::premium()  // premium = Status::YES
```

## Payment Gateway Integration

### Location
`core/app/Http/Controllers/Gateway/[GatewayName]/ProcessController.php`

### Pattern
Each gateway has:
- `ProcessController`: Handles payment initiation
- IPN/Webhook handling in `core/routes/ipn.php`
- Deposit model tracks transactions

### Adding New Gateway
1. Create controller in `Gateway/[Name]/`
2. Add route in `routes/ipn.php` if needed
3. Configure in admin panel (Payment Settings)

## Template System

### Active Template
Templates switchable via admin. Current template via `gs('active_template')` (default: 'basic')

### View Resolution
```php
// In controllers:
return view('Template::home');  // Resolves to active template
return view($activeTemplate . 'layouts.frontend');

// In Blade:
@extends($activeTemplate . 'layouts.frontend')
```

### Template Files
- Views: `core/resources/views/templates/basic/`
- Assets: `assets/templates/basic/`
- Admin views: `core/resources/views/admin/`

## Notification System

### Multi-Channel
Uses `core/app/Notify/Notify.php` to send via Email, SMS, Push:
```php
notify($user, 'PAYMENT_COMPLETE', [
    'amount' => showAmount($amount),
    'trx' => $transaction->trx
]);
```

### Templates
Managed in admin panel with shortcode placeholders (e.g., `{{amount}}`, `{{trx}}`)

## File Storage

### Paths
Configured in database (general_settings table):
- `getFilePath('category')` returns path like 'assets/images/category/'
- `getFileSize('category')` returns allowed dimensions

### Upload Pattern
```php
$image = fileUploader($request->image, getFilePath('category'), getFileSize('category'), $oldImage);
```

## Common Pitfalls

1. **Root vs Core**: Application code lives in `/core`, not root. Always `cd core` for commands
2. **Asset Paths**: Public assets in `/assets`, not `/core/public/assets`
3. **Cache Issues**: After config changes, run `php artisan optimize` or `php artisan config:clear`
4. **Template Changes**: Clear view cache: `php artisan view:clear`
5. **Helper Availability**: Helpers auto-loaded; no need to import
6. **Status Values**: Always use `Status::` constants, never raw integers

## Testing & Debugging

### Debug Mode
Set in `.env`: `APP_DEBUG=true`
Uses `barryvdh/laravel-debugbar` in development

### Admin Access
Default URL: `/admin` (check seeders for credentials)

### Logs
Located in `core/storage/logs/laravel.log`
