# 🍔 DeliverEats — Full Project Documentation

> A multi-role food delivery platform built with **Laravel 13**, **Sanctum API tokens**, **Redis Queues**, and **Google/GitHub OAuth**.

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Tech Stack](#2-tech-stack)
3. [Architecture](#3-architecture)
4. [Database Schema](#4-database-schema)
5. [Authentication & Authorization](#5-authentication--authorization)
6. [API Reference](#6-api-reference)
7. [Redis Queue System](#7-redis-queue-system)
8. [Surge Pricing Engine](#8-surge-pricing-engine)
9. [OAuth (Google & GitHub)](#9-oauth-google--github)
10. [Frontend Views](#10-frontend-views)
11. [Project Structure](#11-project-structure)
12. [Setup & Installation](#12-setup--installation)
13. [Running the Project](#13-running-the-project)
14. [Environment Variables](#14-environment-variables)
15. [Security Notes](#15-security-notes)

---

## 1. Project Overview

**DeliverEats** is a full-stack food delivery web application supporting four distinct user roles:

| Role | Capabilities |
|------|-------------|
| **Customer** | Browse restaurants, add to cart, place orders, track delivery, leave reviews |
| **Restaurant Owner** | Manage menu, view orders, track payouts, update settings |
| **Rider** | View assigned deliveries, update delivery status, track earnings |
| **Admin** | Control tower dashboard, manage users/restaurants, configure surge pricing |

The backend is a **REST API** (Laravel + Sanctum tokens). The frontend is **Blade views** that call the API via JavaScript. Authentication supports both **email/password** and **social login** (Google, GitHub).

---

## 2. Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 13 (PHP 8.3+) |
| Authentication | Laravel Sanctum (token-based) |
| Social OAuth | Laravel Socialite v5 |
| Queue Driver | Redis (via `predis/predis`) |
| Cache Driver | Redis |
| Database | SQLite (dev) / MySQL (production) |
| Frontend | Blade Templates + Vanilla JS |
| Asset Bundler | Vite |

---

## 3. Architecture

```
┌─────────────────────────────────────────────────────┐
│                   Browser / Client                   │
└────────────────────────┬────────────────────────────┘
                         │ HTTP
          ┌──────────────▼──────────────┐
          │      Laravel Application     │
          │  ┌──────────┐ ┌───────────┐ │
          │  │ Web Routes│ │ API Routes│ │
          │  │(Blade +   │ │(Sanctum   │ │
          │  │ OAuth)    │ │ Token)    │ │
          │  └──────────┘ └───────────┘ │
          │  ┌────────────────────────┐ │
          │  │     Service Layer       │ │
          │  │ OrderService │ SurgeService│
          │  └────────────────────────┘ │
          └──────┬─────────────┬────────┘
                 │             │
        ┌────────▼──┐    ┌─────▼──────┐
        │  SQLite/  │    │   Redis    │
        │  MySQL DB │    │ (Queue +   │
        └───────────┘    │  Cache)    │
                         └─────┬──────┘
                               │
                    ┌──────────▼──────────┐
                    │    Queue Workers     │
                    │ orders | notifications│
                    │       | surge        │
                    └─────────────────────┘
```

---

## 4. Database Schema

### `users`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | string | |
| email | string unique | |
| password | string **nullable** | null for OAuth users |
| phone | string(20) nullable | |
| role | enum | `customer`, `restaurant_owner`, `rider`, `admin` |
| avatar | string nullable | |
| address | text nullable | |
| latitude / longitude | decimal(10,7) | |
| is_active | boolean | default true |
| provider | string nullable | `google` or `github` |
| provider_id | string nullable | OAuth provider's user ID |
| deleted_at | timestamp | soft deletes |

### `restaurants`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| user_id | FK → users | owner |
| name, slug | string | slug is unique |
| description | text nullable | |
| logo, cover_image | string nullable | |
| phone | string(20) nullable | |
| address | text | |
| latitude / longitude | decimal(10,7) nullable | |
| opening_hours | json | `{"mon": {"open":"09:00","close":"22:00"}}` |
| min_order_amount | decimal(8,2) | default 0 |
| delivery_radius_km | decimal(5,2) | default 10 |
| avg_rating | decimal(3,2) | updated on review |
| total_reviews | integer | |
| is_active, is_featured | boolean | |

### `menu_categories`
Belongs to a restaurant. Has `name` and `sort_order`.

### `menu_items`
| Column | Type |
|--------|------|
| menu_category_id | FK |
| name | string |
| price | decimal(8,2) |
| is_available | boolean |
| preparation_time_minutes | integer (default 15) |

### `item_variants`
Size/addon variants for a menu item (name, price_modifier).

### `orders`
| Column | Type | Notes |
|--------|------|-------|
| user_id | FK → users | customer |
| restaurant_id | FK | |
| rider_id | FK → users nullable | assigned rider |
| status | enum | `placed → confirmed → preparing → ready_for_pickup → picked_up → delivered / cancelled` |
| subtotal, delivery_fee, surge_multiplier, tax, total | decimal | |
| delivery_address | text | |
| delivery_lat / lng | decimal nullable | |
| confirmed_at … delivered_at | timestamps | state machine timestamps |
| Indexes | `status`, `[user_id, status]`, `[restaurant_id, status]`, `[rider_id, status]` | |

### `order_items`
Each line in an order: `order_id`, `menu_item_id`, `quantity`, `unit_price`, `subtotal`.

### `payments`
| Column | Type |
|--------|------|
| order_id | FK |
| payment_intent_id | string nullable unique (Stripe) |
| method | enum: `card`, `cash`, `wallet` |
| status | enum: `pending`, `processing`, `succeeded`, `failed`, `refunded` |
| amount | decimal(10,2) |
| currency | string(3) default `EGP` |
| stripe_metadata | json nullable |

### `order_histories`
Audit log of every status change on an order.

### `rider_locations`
Real-time lat/lng for active riders (updated by rider app).

### `reviews`
Rating (1–5) + comment per `(user_id, order_id)` pair. Unique constraint prevents duplicate reviews.

### `payouts`
Restaurant payout records with Stripe Transfer ID, status (`pending → completed`).

---

## 5. Authentication & Authorization

### Email / Password (Sanctum Tokens)

```
POST /api/register   → creates user, returns { user, token }
POST /api/login      → validates credentials, returns { user, token }
POST /api/logout     → revokes current token  [auth:sanctum]
GET  /api/profile    → returns authenticated user [auth:sanctum]
```

Include the token in every protected request:
```
Authorization: Bearer <your-sanctum-token>
```

### Role-Based Access (`RoleMiddleware`)

Every protected API group requires both `auth:sanctum` **and** the correct role:

```php
// Example: only 'customer' role can access
Route::middleware(['auth:sanctum', 'role:customer'])->prefix('customer')-> ...
```

Returns `403 Unauthorized` if role doesn't match.

---

## 6. API Reference

### Auth

| Method | Endpoint | Auth | Body |
|--------|----------|------|------|
| POST | `/api/register` | — | `name, email, phone, role, password` |
| POST | `/api/login` | — | `email, password` |
| POST | `/api/logout` | ✅ | — |
| GET | `/api/profile` | ✅ | — |
| GET | `/api/user` | ✅ Sanctum | — |

### Customer Routes `[role:customer]`

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/customer/home` | Home feed data |
| GET | `/api/customer/restaurants/{slug}` | Restaurant details |
| GET | `/api/customer/cart` | Cart contents |
| POST | `/api/customer/checkout` | Place order |
| GET | `/api/customer/orders` | Order history |
| GET | `/api/customer/orders/{id}/track` | Live tracking |
| POST | `/api/customer/orders/{id}/review` | Submit review |

### Rider Routes `[role:rider]`

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/rider/dashboard` | Active deliveries |
| GET | `/api/rider/delivery/{id}` | Delivery details |
| GET | `/api/rider/earnings` | Earnings history |

### Restaurant Owner Routes `[role:restaurant_owner]`

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/restaurant/dashboard` | Overview stats |
| GET | `/api/restaurant/menu` | Menu items |
| GET | `/api/restaurant/orders` | Incoming orders |
| GET | `/api/restaurant/reviews` | Customer reviews |
| GET | `/api/restaurant/payouts` | Payout history |
| GET | `/api/restaurant/settings` | Restaurant config |

### Admin Routes `[role:admin]`

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/admin/dashboard` | Platform KPIs |
| GET | `/api/admin/control-tower` | Live order map |
| GET | `/api/admin/users` | User management |
| GET | `/api/admin/restaurants` | Restaurant management |
| GET | `/api/admin/surge-pricing` | Surge config |

### OAuth

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/auth/google/redirect` | Redirect to Google OAuth |
| GET | `/auth/google/callback` | Google callback → returns `{ user, token }` |
| GET | `/auth/github/redirect` | Redirect to GitHub OAuth |
| GET | `/auth/github/callback` | GitHub callback → returns `{ user, token }` |

---

## 7. Redis Queue System

Three dedicated queues process background work asynchronously:

### Queue Names & Jobs

| Queue | Job | Trigger | Logic |
|-------|-----|---------|-------|
| `orders` | `AssignRiderJob` | Order placed | Finds available rider → assigns → logs history → notifies both parties |
| `notifications` | `SendNotificationJob` | Any event | Logs to file + sends email via configured mailer |
| `surge` | `RecalculateSurgeJob` | Order placed | Computes new surge multiplier → caches in Redis for 5 min |

### Job Retry Configuration

| Job | Tries | Backoff |
|-----|-------|---------|
| `AssignRiderJob` | 3 | 30 sec |
| `SendNotificationJob` | 3 | — |
| `RecalculateSurgeJob` | 2 | — |

### Running Workers

```bash
# All queues (recommended for dev)
php artisan queue:work redis --queue=orders,notifications,surge --tries=3

# Individual queues
php artisan queue:work redis --queue=orders
php artisan queue:work redis --queue=notifications
php artisan queue:work redis --queue=surge
```

### Dispatching Jobs (Example)

```php
use App\Jobs\AssignRiderJob;
use App\Jobs\SendNotificationJob;
use App\Jobs\RecalculateSurgeJob;

// After order is created:
AssignRiderJob::dispatch($order);
SendNotificationJob::dispatch($user, 'Subject', 'Message body');
RecalculateSurgeJob::dispatch($restaurantId);
```

---

## 8. Surge Pricing Engine

**`App\Services\SurgeService`** dynamically adjusts delivery pricing based on real-time demand.

### How It Works

1. On each order placed, `RecalculateSurgeJob` is dispatched
2. `SurgeService::recalculate()` counts active orders for the restaurant in the past 30 minutes
3. The multiplier is computed from a tier table and **cached in Redis** for 5 minutes
4. `OrderService::placeOrder()` reads the cached multiplier before computing the order total

### Surge Tiers

| Active Orders (last 30 min) | Multiplier |
|-----------------------------|-----------|
| 0 – 4 | 1.0× (no surge) |
| 5 – 9 | 1.25× |
| 10 – 19 | 1.5× |
| 20 – 29 | 1.75× |
| 30 – 39 | 2.0× |
| 40+ | 2.5× (max) |

### Redis Cache Keys

```
surge:restaurant:{id}   → float multiplier, TTL 300s
queues:orders           → Redis list (job payloads)
queues:notifications    → Redis list
queues:surge            → Redis list
```

---

## 9. OAuth (Google & GitHub)

### Flow

```
1. User visits  GET /auth/google/redirect
2. Redirected to Google consent screen
3. Google redirects back to  GET /auth/google/callback
4. SocialAuthController finds or creates the user
5. Returns JSON: { success, user, token }
6. Frontend stores token and proceeds as normal Sanctum user
```

### Account Linking Logic (`SocialAuthController`)

```
Provider callback received
  └─ Lookup by (provider, provider_id)
       ├─ Found → return existing user + new token
       └─ Not found → lookup by email
            ├─ Found → link OAuth provider to existing account
            └─ Not found → create new customer user (password = null)
  → Revoke old OAuth tokens
  → Issue fresh Sanctum token
  → Return JSON response
```

### Setup — Google

1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. APIs & Services → Credentials → Create OAuth 2.0 Client
3. Set **Authorized redirect URI**: `http://localhost:8000/auth/google/callback`
4. Add to `.env`:
```ini
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-secret
```

### Setup — GitHub

1. Go to [GitHub Developer Settings](https://github.com/settings/developers)
2. New OAuth App
3. Set **Authorization callback URL**: `http://localhost:8000/auth/github/callback`
4. Add to `.env`:
```ini
GITHUB_CLIENT_ID=your-client-id
GITHUB_CLIENT_SECRET=your-secret
```

---

## 10. Frontend Views

All views are Blade templates under `resources/views/`:

| Path | Description |
|------|-------------|
| `landing.blade.php` | Public landing page |
| `auth/login.blade.php` | Login form |
| `auth/register.blade.php` | Registration form |
| `customer/home.blade.php` | Restaurant browse feed |
| `customer/restaurant.blade.php` | Restaurant detail + menu |
| `customer/cart.blade.php` | Shopping cart |
| `customer/checkout.blade.php` | Checkout + payment |
| `customer/orders.blade.php` | Order history |
| `customer/track-order.blade.php` | Live order tracker |
| `customer/review.blade.php` | Leave a review |
| `customer/profile.blade.php` | User profile |
| `restaurant/dashboard.blade.php` | Restaurant KPIs |
| `restaurant/menu.blade.php` | Menu management |
| `restaurant/orders.blade.php` | Incoming orders |
| `restaurant/reviews.blade.php` | Reviews received |
| `restaurant/payouts.blade.php` | Payout history |
| `restaurant/settings.blade.php` | Restaurant settings |
| `rider/dashboard.blade.php` | Active deliveries |
| `rider/delivery.blade.php` | Delivery detail |
| `rider/earnings.blade.php` | Earnings breakdown |
| `admin/dashboard.blade.php` | Platform overview |
| `admin/control-tower.blade.php` | Live order map |
| `admin/users.blade.php` | User management |
| `admin/restaurants.blade.php` | Restaurant management |
| `admin/surge-pricing.blade.php` | Surge pricing config |

---

## 11. Project Structure

```
DeliverEats/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # Email/password auth
│   │   │   ├── SocialAuthController.php    # Google & GitHub OAuth
│   │   │   └── Api/
│   │   │       ├── OrderController.php
│   │   │       ├── RestaurantController.php
│   │   │       ├── RiderController.php
│   │   │       └── DispatchController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php          # Role-based access control
│   ├── Jobs/
│   │   ├── AssignRiderJob.php              # Rider assignment (queue: orders)
│   │   ├── SendNotificationJob.php         # Email/log notify (queue: notifications)
│   │   └── RecalculateSurgeJob.php         # Surge pricing (queue: surge)
│   ├── Models/
│   │   ├── User.php
│   │   ├── Restaurant.php
│   │   ├── MenuItem.php / MenuCategory.php
│   │   ├── ItemVariant.php
│   │   ├── Order.php / OrderItem.php
│   │   ├── OrderHistory.php
│   │   ├── Payment.php / Payout.php
│   │   ├── RiderLocation.php
│   │   └── Review.php
│   └── Services/
│       ├── OrderService.php                # placeOrder() — orchestrates jobs
│       ├── SurgeService.php                # Redis-cached surge multiplier
│       ├── PaymentService.php
│       └── DispatchService.php
├── config/
│   ├── services.php                        # Google + GitHub OAuth config
│   └── queue.php                           # Redis queue connections
├── database/
│   └── migrations/                         # 16 migrations
├── routes/
│   ├── web.php                             # Blade + OAuth routes
│   └── api.php                             # Sanctum-protected API routes
└── resources/views/                        # 25 Blade templates
```

---

## 12. Setup & Installation

### Prerequisites

- PHP 8.3+
- Composer
- Node.js & npm
- SQLite (built-in) **or** MySQL
- Redis server

### Quick Start

```bash
# 1. Clone and enter the project
git clone <repo-url> DeliverEats
cd DeliverEats

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate app key
php artisan key:generate

# 6. Run migrations
php artisan migrate

# 7. Build frontend assets
npm run build
```

### Redis (Windows)

Redis is bundled at `C:\Redis\redis-server.exe` (auto-downloaded during setup).

```powershell
# Start Redis
Start-Process -FilePath "C:\Redis\redis-server.exe" -WindowStyle Hidden

# Verify
C:\Redis\redis-cli.exe ping   # → PONG
```

---

## 13. Running the Project

### Option A — All-in-one (recommended)

```bash
composer dev
```

This starts **4 processes concurrently**:
- `php artisan serve` — Laravel app on `http://localhost:8000`
- `php artisan queue:listen --tries=1` — Queue worker (all Redis queues)
- `php artisan pail` — Real-time log viewer
- `npm run dev` — Vite asset bundler with HMR

### Option B — Manual

```bash
# Terminal 1: App server
php artisan serve

# Terminal 2: Queue worker (all 3 named queues)
php artisan queue:work redis --queue=orders,notifications,surge --tries=3

# Terminal 3: Vite (for frontend changes)
npm run dev
```

### Access Points

| URL | Description |
|-----|-------------|
| `http://localhost:8000` | Landing page |
| `http://localhost:8000/login` | Login |
| `http://localhost:8000/register` | Register |
| `http://localhost:8000/auth/google/redirect` | Google OAuth |
| `http://localhost:8000/auth/github/redirect` | GitHub OAuth |
| `http://localhost:8000/browse` | Customer browse |
| `http://localhost:8000/restaurant/dashboard` | Restaurant dashboard |
| `http://localhost:8000/rider/dashboard` | Rider dashboard |
| `http://localhost:8000/admin/dashboard` | Admin dashboard |

---

## 14. Environment Variables

```ini
# Application
APP_NAME=DeliverEats
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

# Database (SQLite default, or MySQL)
DB_CONNECTION=sqlite
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=deliverats
# DB_USERNAME=root
# DB_PASSWORD=

# Redis (Queue + Cache)
QUEUE_CONNECTION=redis
CACHE_STORE=redis
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

# GitHub OAuth
GITHUB_CLIENT_ID=your-github-client-id
GITHUB_CLIENT_SECRET=your-github-secret
GITHUB_REDIRECT_URI="${APP_URL}/auth/github/callback"

# Mail (log driver in dev → no real emails sent)
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@deliverats.com"
MAIL_FROM_NAME="DeliverEats"
```

---

## 15. Security Notes

- **Sanctum tokens** are used for all API authentication — never cookies (API is stateless)
- **Role middleware** (`role:customer|rider|restaurant_owner|admin`) enforces access at the route level
- **OAuth users** have `password = null` — no password-based login possible for their account unless set explicitly
- **Sensitive job data** is serialized via Laravel's model binding — no raw credentials in queue payloads
- **Stripe metadata** stored in `payments.stripe_metadata` — raw card data is **never** stored
- **Soft deletes** on `users` and `restaurants` — admin deactivation is reversible
- **Unique constraint** on `reviews(user_id, order_id)` — prevents duplicate review spam
- **BCRYPT_ROUNDS=12** — strong password hashing
- All queue jobs have **retry limits** to prevent infinite failure loops

---

## Appendix: Order Status State Machine

```
placed
  └─► confirmed (restaurant confirms)
        └─► preparing (kitchen starts)
              └─► ready_for_pickup (food is ready)
                    └─► picked_up (rider picks up)
                          └─► delivered ✅

Any state → cancelled ❌
```

Each transition is timestamped and logged to `order_histories`.
