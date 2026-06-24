# Travel Planner

Travel Planner is a Laravel web application for collecting travel destinations and coordinating trip itineraries in private groups. Signed-in users can browse a shared destination catalog, contribute locations, create groups, invite other users, build group itineraries, and export a trip to PDF.

The current implementation now uses groups as the planning workspace. Public itinerary share links are no longer the collaboration model.

## Start Here

- [Product intent and scope](docs/PRODUCT.md)
- [Codebase guide](docs/CODEBASE_GUIDE.md)
- [Local development runbook](docs/DEVELOPMENT.md)
- [Instructions for coding agents](AGENTS.md)

## Current Features

- Laravel Breeze registration, login, password reset, and profile pages.
- Shared travel-location catalog with search, images, and embedded Google Maps views.
- Group workspaces with owner, editor, and viewer memberships.
- Private group destinations for frequently used places and meeting points.
- Optional Google Maps place picker for auto-filling private group destinations.
- Time-limited and use-limited group invitation links.
- Group-owned itineraries with scheduled locations and per-stop notes.
- PDF itinerary export through `barryvdh/laravel-dompdf`.
- Admin console for internal category management, user information updates, and itinerary moderation.

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

Itineraries are visible through group membership, not by possessing a public itinerary URL. Admin moderation remains separate from group ownership.
