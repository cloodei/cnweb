# Local Development

## Prerequisites

- PHP `>= 8.3`
- Composer
- Node.js and npm
- PHP extensions required by Laravel and this app, including `fileinfo`, `mbstring`, and the PDO driver for your selected database
- `pdo_sqlite` when using the default SQLite configuration or running the default PHPUnit suite

The committed `.env.example` uses SQLite. That is the simplest local setup.

## First-Time Setup With SQLite

PowerShell:

```powershell
composer install
npm install
Copy-Item .env.example .env
php artisan key:generate
New-Item database/database.sqlite -ItemType File -Force
php artisan migrate --seed
php artisan storage:link
```

Bash:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
```

`php artisan storage:link` is required before uploaded location images can be served from `/storage/...`.

Do not use `php artisan storage:link --relative` unless `symfony/filesystem` is installed. The normal command is sufficient for this project.

## MySQL Alternative

Set these values in `.env`, create the database, then run migrations:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=travel_planner
DB_USERNAME=root
DB_PASSWORD=
```

```powershell
php artisan migrate --seed
```

Do not commit `.env`.

## Run The Application

The standard development command starts the Laravel server, log viewer, and Vite:

```powershell
composer run dev
```

Open `http://127.0.0.1:8000`.

For a minimal two-terminal setup:

```powershell
php artisan serve
```

```powershell
npm run dev
```

## Demo Seed Data

`php artisan migrate --seed` creates or updates three local demo accounts:

| Account | Email | Password |
| --- | --- | --- |
| Intended admin | `admin@gmail.com` | `12345678` |
| User A | `usera@gmail.com` | `12345678` |
| User B | `userb@gmail.com` | `12345678` |

These credentials are for local development only. The seeder assigns `role` explicitly, so the admin account should be able to reach the admin pages after seeding.

## Manual Smoke Test

1. Open `/` and register or log in.
2. Open `/dashboard` and confirm global category and location totals plus your itinerary total.
3. Open `/categories` and browse a category.
4. Open `/locations`, create a destination with an image, search for it, filter by category, and open its map detail page.
5. Open `/itineraries`, create a trip, add destination stops with visit times and notes, and remove one stop.
6. Download the itinerary PDF.
7. Copy the itinerary share URL and open it in a private browser window. Confirm it is readable but has no editing controls.
8. With a working admin account, open `/admin/users` and `/admin/itineraries`.

## Verification Commands

```powershell
composer validate --no-check-publish
npm run build
php artisan route:list --except-vendor
php artisan test
```

The test suite uses in-memory SQLite from `phpunit.xml`. If tests fail with `could not find driver`, enable the `pdo_sqlite` extension for the PHP CLI runtime and rerun the suite.

## Useful Commands

```powershell
php artisan migrate:status
php artisan migrate:fresh --seed
php artisan storage:link
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

`php artisan migrate:fresh --seed` destroys local database data. Use it only when resetting a development database is intentional.

## Troubleshooting

### Uploaded Images Do Not Render

Run:

```powershell
php artisan storage:link
```

Then verify that `public/storage` points to `storage/app/public`.

### The Landing Page Fails With A Missing Vite Manifest

For a production-style asset check, run:

```powershell
npm run build
```

During development, run `npm run dev` or `composer run dev`.

### Seeded Admin Cannot Reach Admin Pages

Run `php artisan migrate:status` and confirm the users table has been migrated, then run `php artisan db:seed`. If the account already exists, the seeder updates its role.

### Category Delete Is Blocked

Categories with locations cannot be deleted through the application. Move or delete the locations first, then retry the category deletion.
