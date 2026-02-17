# PhoneBD Discussion Platform

A complete visitor discussion platform for **discuss.phonebd.net** built with Laravel 12, Filament 5, and Tailwind CSS.

## 🚀 Features

### Visitor Features (No Login Required)
- ✅ Ask questions about specific phones
- ✅ Reply to existing discussions
- ✅ Vote "Helpful" on discussions
- ✅ Report spam or misleading content
- ✅ Read all discussions
- ✅ Optional guest name for anonymous users

### Logged-in User Benefits
- ✅ Edit own comments
- ✅ Reply notifications (coming soon)
- ✅ Verified badge
- ✅ Auto-approval of comments (for trusted users)

### Admin Panel Features
- ✅ View all discussions and replies
- ✅ Approve or reject content
- ✅ Delete replies
- ✅ View reports
- ✅ Ban IP or users (coming soon)
- ✅ Mark users as Trusted
- ✅ Pin important discussions

### Security & Anti-Spam
- ✅ IP-based rate limiting (5 comments/hour)
- ✅ Duplicate content detection
- ✅ Max 1 external link per comment
- ✅ HTML sanitization
- ✅ Report system
- ✅ Content moderation workflow

## 📋 Requirements

- PHP 8.2+
- MySQL 5.7+
- Composer
- Node.js & NPM

## 🛠️ Installation

### 1. Clone & Install Dependencies

```bash
cd d:/xampp/htdocs/discuss
composer install
npm install
```

### 2. Environment Setup

The `.env` file is already configured with:
- Database: `discuss`
- Main database: `phonebd_main` (for phone data)

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. Run Migrations

```bash
php artisan migrate
```

### 5. Seed Test Data (Optional)

```bash
php artisan db:seed --class=TestDataSeeder
```

This creates:
- **Admin**: admin@phonebd.net / password
- **Moderator**: moderator@phonebd.net / password
- **User**: user@phonebd.net / password
- 5 test phones with discussions and replies

### 6. Build Assets

```bash
npm run build
```

For development:
```bash
npm run dev
```

### 7. Start Development Server

```bash
php artisan serve
```

Or use the combined dev command:
```bash
composer run dev
```

This starts:
- PHP server
- Queue worker
- Log viewer
- Vite dev server

## 🌐 Access Points

- **Visitor Discussions**: `http://localhost:8000/{phone-slug}`
  - Example: `http://localhost:8000/xiaomi-redmi-note-13`
- **Admin Panel**: `http://localhost:8000/admin`
- **Login**: `http://localhost:8000/login`
- **Register**: `http://localhost:8000/register`

## 📁 Project Structure

```
discuss/
├── app/
│   ├── Filament/
│   │   └── Resources/
│   │       └── DiscussionResource.php    # Admin panel resource
│   ├── Http/
│   │   └── Controllers/
│   │       └── DiscussionController.php  # Visitor-facing controller
│   └── Models/
│       ├── Phone.php
│       ├── Discussion.php
│       ├── DiscussionReply.php
│       ├── DiscussionVote.php
│       └── DiscussionReport.php
├── database/
│   ├── migrations/                       # Database schema
│   └── seeders/
│       └── TestDataSeeder.php           # Test data
├── resources/
│   └── views/
│       └── discussions/
│           └── show.blade.php           # Discussion page
└── routes/
    └── web.php                          # Application routes
```

## 🗄️ Database Schema

### Users
- Social login support (Google, Facebook)
- Role-based access (admin, moderator, user)
- Trusted user flag

### Phones
- Slug-based routing
- Name

### Discussions
- Phone association
- User or guest author
- Content moderation (pending/approved/rejected)
- Pin support
- IP tracking

### Discussion Replies
- Nested under discussions
- Same moderation workflow

### Discussion Votes
- Helpful voting system
- IP and user tracking

### Discussion Reports
- User-generated reports
- Admin review workflow

## 🔧 Configuration

### Multi-Database Setup

The project uses two databases:

1. **discuss** (main): Discussion platform data
2. **phonebd_main** (reference): Phone product data

Both are configured in `.env`:

```env
# Main discussion database
DB_CONNECTION=mysql
DB_DATABASE=discuss

# Phone data reference
DB_CONNECTION_MAIN=mysql
DB_DATABASE_MAIN=phonebd_main
```

### Rate Limiting

Configure in `app/Http/Controllers/DiscussionController.php`:

```php
// 5 attempts per hour
RateLimiter::tooManyAttempts($key, 5)
```

### Content Moderation

- **Trusted users**: Auto-approved
- **New/guest users**: Pending moderation
- **Admin/moderator**: Review in Filament panel

## 🎨 Customization

### Tailwind Configuration

Edit `tailwind.config.js`:

```javascript
module.exports = {
  theme: {
    extend: {
      colors: {
        // Add custom colors
      }
    }
  }
}
```

### Admin Panel Theme

Edit `app/Providers/Filament/AdminPanelProvider.php`:

```php
->colors([
    'primary' => Color::Amber,
])
```

## 🔐 Social Login Setup

### 1. Install Socialite Providers

Already installed: `laravel/socialite`

### 2. Configure Services

Add to `config/services.php`:

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],

'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT_URI'),
],
```

### 3. Add to .env

```env
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://discuss.phonebd.net/auth/google/callback

FACEBOOK_CLIENT_ID=your_app_id
FACEBOOK_CLIENT_SECRET=your_app_secret
FACEBOOK_REDIRECT_URI=http://discuss.phonebd.net/auth/facebook/callback
```

### 4. Create Social Login Controller

```bash
php artisan make:controller Auth/SocialLoginController
```

## 📊 Performance Optimization

### Recommended Indexes

Add to migrations for better performance:

```php
$table->index('ip_address');
$table->index('status');
$table->index(['phone_id', 'status']);
$table->index(['discussion_id', 'status']);
```

### Caching

Configure in `.env`:

```env
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## 🚀 Production Deployment

### 1. Environment

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://discuss.phonebd.net
```

### 2. Optimize

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

### 3. Queue Worker

```bash
php artisan queue:work --tries=3
```

Or use Supervisor for process management.

### 4. Scheduler

Add to cron:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## 📝 Testing

### Create Test Phone

```bash
php artisan tinker
```

```php
\App\Models\Phone::create([
    'slug' => 'test-phone',
    'name' => 'Test Phone'
]);
```

### Visit Discussion Page

Navigate to: `http://localhost:8000/test-phone`

## 🐛 Troubleshooting

### Filament Admin Panel Not Loading

Clear cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Routes Not Working

```bash
php artisan route:clear
php artisan route:cache
```

### Database Connection Error

Check `.env` database credentials and ensure MySQL is running.

## 📚 Documentation

- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Alpine.js Documentation](https://alpinejs.dev)

## 🤝 Contributing

This is a private project for PhoneBD.

## 📄 License

Proprietary - All rights reserved.

## 👨‍💻 Support

For issues or questions, contact the development team.

---

**Built with ❤️ using Laravel, Filament, and Tailwind CSS**
