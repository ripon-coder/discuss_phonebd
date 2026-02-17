You are a senior Laravel developer.

Build a complete visitor discussion platform for the subdomain:
discuss.phonebd.net

This project is ONLY for the discussion system.
No main-site logic, no ecommerce, no review writing.

--------------------------------------------------
Multi Database
--------------------------------------------------
mobile related database have phonebd_main and this project have DATABASE is only discuss

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=discuss
DB_USERNAME=root
DB_PASSWORD=

DB_CONNECTION_MAIN=mysql
DB_HOST_MAIN=localhost
DB_PORT_MAIN=3306
DB_DATABASE_MAIN=phonebd_main
DB_USERNAME_MAIN=root
DB_PASSWORD_MAIN=

--------------------------------------------------
TECH STACK
--------------------------------------------------
- Laravel (latest stable)
- Blade templates
- Tailwind CSS (visitor UI)
- Alpine.js (light interactivity only)
- Filament (admin panel)
- Laravel Socialite (Google & Facebook login)
- MySQL
- No Vue, no React, no Next.js

--------------------------------------------------
CORE PURPOSE
--------------------------------------------------
- Visitors can discuss specific mobile phones
- One discussion thread per phone
- Phones are identified by slug (e.g. xiaomi-redmi-note-13)
- Discussion content is user-generated (UGC)

URL format:
https://discuss.phonebd.net/{phone-slug}

--------------------------------------------------
VISITOR FEATURES (LOGIN NOT REQUIRED)
--------------------------------------------------
Visitors can:
- Ask a question about the phone
- Reply to existing discussions
- Vote "Helpful 👍"
- Report spam or misleading content
- Read all discussions

Visitors cannot:
- Edit or delete comments
- Post more than rate limits
- Post multiple external links

Guest fields:
- guest_name (optional)
- Email not required

--------------------------------------------------
SOCIAL LOGIN (OPTIONAL)
--------------------------------------------------
- Google login
- Facebook login
- Login is never mandatory

Logged-in users get:
- Edit own comments
- Reply notifications
- Verified badge
- Auto-approval of comments

Guest + logged-in users must work together seamlessly.

--------------------------------------------------
ANTI-SPAM & SECURITY
--------------------------------------------------
- IP-based rate limiting (e.g. 5 comments/hour)
- Duplicate content detection
- Max 1 external link per comment
- HTML sanitization
- Report system
- Admin ban by IP or user

--------------------------------------------------
ADMIN PANEL (FILAMENT)
--------------------------------------------------
Admin can:
- View all discussions and replies
- Approve or reject content
- Delete replies
- View reports
- Ban IP or users
- Mark users as Trusted
- Pin important discussions

Roles:
- Super Admin
- Moderator

--------------------------------------------------
DATABASE STRUCTURE (MINIMUM)
--------------------------------------------------

users
- id
- name
- email
- provider
- provider_id
- avatar
- is_trusted

phones
- id
- slug
- name

discussions
- id
- phone_id
- user_id (nullable)
- guest_name (nullable)
- content
- ip_address
- status (pending / approved)
- created_at

discussion_replies
- id
- discussion_id
- user_id (nullable)
- guest_name (nullable)
- content
- ip_address
- created_at

discussion_votes
- id
- discussion_id
- user_id or ip_address

discussion_reports
- id
- discussion_id
- reason

--------------------------------------------------
UI RULES (VISITOR SIDE)
--------------------------------------------------
- Clean, minimal Tailwind UI
- Mobile-first design
- Inline reply (max 1 level)
- No heavy JavaScript
- Clear "Ask a Question" section

--------------------------------------------------
SEO & PERFORMANCE
--------------------------------------------------
- Discussion pages indexable
- Fast page load
- Tailwind purge enabled
- Pagination for long threads

--------------------------------------------------
DELIVERABLES
--------------------------------------------------
- Laravel migrations
- Controllers
- Routes
- Blade views (Tailwind)
- Filament resources
- Social login setup
- Spam protection logic
- Ready for production deployment

Build the system clean, fast, scalable, and suitable for Bangladesh user behavior.
