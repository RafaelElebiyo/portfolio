# Portfolio v2

A production-ready personal portfolio built with **PHP 8**, **MySQL (PDO)**, **Bootstrap 5**, and **Vanilla JS** — with a full security layer, dark/light theming, multi-language support, and client-side pagination.

---

## Stack

| Layer     | Technology |
|-----------|-----------|
| Backend   | PHP 8 · PDO (prepared statements) |
| Database  | MySQL 8 / MariaDB |
| Frontend  | Bootstrap 5 · Vanilla JS (ES2020) |
| Fonts     | Outfit + Plus Jakarta Sans (Google Fonts) |
| Icons     | Font Awesome 6 Free |
| PDF       | Dompdf |
| Mail      | PHPMailer |

---

## Features

- **Dark / Light theme** — persisted in `localStorage`, respects `prefers-color-scheme`
- **i18n (ES / EN / FR)** — session-based, URL-switch via `?lang=XX`
- **Security hardening:**
  - PDO prepared statements everywhere — no raw input in queries
  - CSRF tokens on every form (SHA-256, TTL 1h)
  - Session-based rate limiter (5 contact submissions / hour)
  - Honeypot anti-spam field
  - Security headers via PHP + `.htaccess` (CSP, HSTS, X-Frame, etc.)
  - Input sanitised through `Sanitizer` helper before use
- **Modular architecture:** `config/ middleware/ services/ helpers/ includes/ lang/`
- **Client-side pagination** — configurable `perPage` in `config/app.php`
- **Lazy loading** — `IntersectionObserver` for images + animated skill bars
- **Toast notifications** — `Toast.success / .error / .warning / .info`
- **Reusable project modal** — skeleton loading state, carousel, code sample
- **Responsive** — mobile / tablet / desktop / TV (fluid type + 8dp rhythm)
- **AJAX contact form** — no page reload, instant toast feedback
- **PDF CV download** — Dompdf, generated server-side

---

## Installation

```bash
# 1. Clone
git clone https://github.com/RafaelElebiyo/portfolio portfolio-v2
cd portfolio-v2

# 2. Install PHP dependencies
composer install

# 3. Create database and import schema
mysql -u root -p -e "CREATE DATABASE portfolio_db CHARACTER SET utf8mb4"
mysql -u root -p portfolio_db < database.sql

# 4. Configure the app — edit config/app.php OR set environment variables:
export DB_HOST=localhost
export DB_NAME=portfolio_db
export DB_USER=root
export DB_PASS=secret
export MAIL_HOST=smtp.gmail.com
export MAIL_USER=you@gmail.com
export MAIL_PASS=app-password
export MAIL_TO=you@gmail.com

# 5. Point your web server (Apache/XAMPP) document root to this folder
# 6. Ensure error_logs/ is writable
chmod 755 error_logs
```

---

## Directory Structure

```
portfolio-v2/
├── config/
│   └── app.php               # Centralised configuration
├── middleware/
│   ├── SecurityHeaders.php   # HTTP security headers
│   ├── CsrfMiddleware.php    # CSRF token generation & validation
│   └── RateLimiter.php       # Session-based rate limiting
├── services/
│   ├── Database.php          # PDO singleton
│   ├── BaseService.php       # Prepared-statement helpers
│   ├── ResumeService.php     # CV data queries
│   ├── ProjectsService.php   # Projects queries
│   └── ContactService.php    # PHPMailer wrapper
├── helpers/
│   ├── Sanitizer.php         # Input sanitisation & validation
│   └── translation.php       # t(), lang_url(), current_lang()
├── includes/
│   ├── bootstrap.php         # Application bootstrap (call first)
│   ├── head.php              # <head> meta, fonts, CSS
│   ├── header.php            # Top bar (brand, theme, lang, mobile toggle)
│   ├── navigation.php        # Main nav
│   ├── footer.php            # Footer
│   ├── toast_and_modal.php   # Toast container + project modal HTML
│   ├── contact-handler.php   # AJAX endpoint for contact form
│   └── generate-pdf.php      # Dompdf CV renderer
├── lang/
│   ├── es.php / en.php / fr.php
├── assets/
│   ├── css/main.css          # Full design system (tokens, dark/light, responsive)
│   └── js/
│       ├── theme.js          # Dark/light toggle
│       ├── toast.js          # Toast notification system
│       ├── lazy.js           # Image lazy load + scroll animations
│       ├── pagination.js     # Client-side pagination
│       ├── modal.js          # Project detail modal
│       └── app.js            # Global init, form validation, filters
├── error_pages/
│   ├── 403.php
│   └── 500.php
├── index.php / about.php / projects.php / resume.php / contact.php
├── database.sql              # Full schema + sample data
├── composer.json
└── .htaccess                 # Security + routing + caching
```

---

## Security Checklist

- [x] PDO prepared statements — all queries
- [x] CSRF tokens (hash_equals comparison)
- [x] Rate limiting — contact form (5/h per IP+UA fingerprint)
- [x] Honeypot anti-bot field
- [x] Input sanitization (Sanitizer class)
- [x] Security headers (CSP, HSTS, X-Frame-Options, Referrer-Policy…)
- [x] Session hardening (httponly, samesite=Lax, secure flag)
- [x] Error display off in production (config/app.php debug=false)
- [x] Vendor/config/services blocked via .htaccess
- [x] No sensitive data exposed in JSON responses

---

## Customization

1. **Personal data** — update rows in `personal_info`, `technical_skills`, `work_experience`, etc.
2. **Colors** — edit CSS variables in `assets/css/main.css` `:root` block
3. **Email** — set `MAIL_*` env variables or edit `config/app.php`
4. **Pagination** — change `projects_per_page` in `config/app.php`
5. **Languages** — add a new file in `lang/` and register it in `config/app.php › supported_langs`
