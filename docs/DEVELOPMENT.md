# Local Development

## Prerequisites

- PHP `>= 8.3`
- Composer
- Node.js and npm
- PHP extensions required by Laravel and this app, including `fileinfo`, `mbstring`, and the PDO driver for your selected database
- `pdo_sqlite` when using the default SQLite configuration or running the default PHPUnit suite

The committed `.env.example` uses SQLite. That is the simplest local setup.

Google Maps place selection is optional. To enable map-assisted private group destination creation, set:

```dotenv
GOOGLE_MAPS_BROWSER_KEY=your-browser-key
```

The key must be allowed for browser use with the Maps JavaScript API and Places library. Without it, destination forms fall back to manual entry.

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

`php artisan migrate --seed` creates or updates a complete local demo dataset:

| Account | Email | Password |
| --- | --- | --- |
| Admin | `admin@gmail.com` | `12345678` |
| User A | `usera@gmail.com` | `12345678` |
| User B | `userb@gmail.com` | `12345678` |
| User C | `chau@gmail.com` | `12345678` |
| User D | `huy@gmail.com` | `12345678` |

The suite also includes seven categories, fourteen Vietnamese shared destinations, three travel groups, private group destinations, five group itineraries, and scheduled stops with visit times and notes. It is deterministic and safe to rerun for these records. These credentials are for local development only.

## Manual Smoke Test

1. Open `/` and register or log in.
2. Open `/dashboard` and confirm global location totals plus your itinerary total.
3. Open `/locations`, create a destination with an image, search for it, and open its map detail page.
4. Open `/categories` and confirm it redirects back to `/locations`.
5. Open `/groups`, create a group, and confirm you are listed as owner.
6. Open the group's private destinations page, add a destination manually or with Google Maps if configured, and confirm it stays under that group.
7. From the group itineraries page, create a trip, add stops from both private group destinations and the shared catalog, and remove one stop.
8. Create an invite link with a short duration and max-use count. Open it as another signed-in user and confirm the user can join with the selected role.
9. Download the itinerary PDF from a group itinerary page.
10. With the seeded admin account, open `/admin/users`, edit a user's name/email/role, and confirm no delete-user action exists.
11. Open `/admin/categories`, create or rename a category, and confirm categories containing locations cannot be deleted.
12. Open `/admin/itineraries` and verify the seeded group trips are available for moderation.

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
