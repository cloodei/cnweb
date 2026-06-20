# Travel Planner

Travel Planner is a Laravel web application for collecting travel destinations and building trip itineraries. Signed-in users can browse a shared destination catalog, contribute locations, build personal schedules, export a trip to PDF, and publish a read-only itinerary link.

The longer-term product direction is collaborative trip planning: a group should be able to plan a trip together, coordinate destinations, and track the plan in one place. The current implementation is an early foundation for that product, not a complete group workspace yet.

## Start Here

- [Product intent and scope](docs/PRODUCT.md)
- [Codebase guide](docs/CODEBASE_GUIDE.md)
- [Local development runbook](docs/DEVELOPMENT.md)
- [Instructions for coding agents](AGENTS.md)

## Current Features

- Laravel Breeze registration, login, password reset, and profile pages.
- Shared travel-location catalog with categories, search, filtering, images, and embedded Google Maps views.
- User-owned itineraries with scheduled locations and per-stop notes.
- PDF itinerary export through `barryvdh/laravel-dompdf`.
- Public read-only itinerary share links.
- Admin console for category management, user information updates, and itinerary moderation.

## Technology

| Area | Choice |
| --- | --- |
| Backend | PHP 8.3+, Laravel 13 |
| Rendering | Blade templates |
| Frontend | Vite, Tailwind CSS, Alpine.js |
| Database | SQLite by default; Laravel also supports MySQL, MariaDB, PostgreSQL, and SQL Server |
| Authentication | Laravel Breeze session authentication |
| File storage | Laravel `public` disk for location images |
| PDF export | DOMPDF |

## Quick Start

The detailed setup guide is in [docs/DEVELOPMENT.md](docs/DEVELOPMENT.md). For a fresh PowerShell checkout using SQLite:

```powershell
composer install
npm install
Copy-Item .env.example .env
php artisan key:generate
New-Item database/database.sqlite -ItemType File -Force
php artisan migrate --seed
php artisan storage:link
composer run dev
```

Open `http://127.0.0.1:8000`.

Your PHP CLI must have `pdo_sqlite` enabled when using SQLite and when running the default PHPUnit configuration.

## Important Boundary

The application does **not** currently have group memberships, invitations, collaborative itinerary editing, or an activity history. A public share link is read-only and should not be treated as group collaboration. Read [docs/PRODUCT.md](docs/PRODUCT.md) before extending that area.
