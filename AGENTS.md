# AGENTS.md

## Mission

This repository is a Laravel travel-planning application. Its intended direction is collaborative trip planning for groups. Its implemented baseline is narrower: a shared destination catalog, user-owned itineraries, public read-only share links, PDF export, and basic admin moderation.

Do not describe or build on group collaboration as if it already exists. Read `docs/PRODUCT.md` before adding collaboration features.

## Read First

1. Read `README.md`.
2. Read `docs/PRODUCT.md` for product vocabulary and implemented-versus-planned boundaries.
3. Read `docs/CODEBASE_GUIDE.md` for modules, routes, relationships, and known gaps.
4. Read `docs/DEVELOPMENT.md` before running or changing local setup.
5. Inspect the relevant route, controller, model, migration, Blade view, and test files before editing.

## Current Module Boundaries

| Module | Main files | Ownership rule |
| --- | --- | --- |
| Authentication and profiles | `routes/auth.php`, `app/Http/Controllers/Auth`, `ProfileController`, auth/profile views | Laravel Breeze baseline |
| Dashboard | `routes/web.php`, `resources/views/dashboard.blade.php` | Signed-in landing page |
| Categories | `CategoryController`, `Category`, category views | Signed-in read; intended admin write |
| Locations | `LocationController`, `Location`, location views | Shared catalog; contributor or admin edit/delete |
| Itineraries | `ItineraryController`, `Itinerary`, itinerary views | Current owner-only workspace |
| Scheduled stops | `itinerary_location` migration, `Itinerary::locations()` | Pivot data stores visit time and note |
| Public sharing | `ItineraryController::shared()`, `itineraries/shared.blade.php` | Anonymous read-only URL |
| Admin moderation | `AdminController`, `AdminMiddleware`, admin views | `role === 'admin'` |

## Product Invariants

- A `Location` is shared catalog content.
- An `Itinerary` is a trip plan.
- `itinerary_location` is the scheduled-stop relation; keep trip-specific time and note data there.
- Current itinerary mutations require the itinerary owner.
- Public share URLs are read-only. Never grant edit access from a public share URL.
- Location mutation is limited to the contributor or an admin.
- Category deletion cascades into locations and scheduled stops. Treat it as a high-impact operation.
- Admin moderation is separate from future group membership.

## Before Adding Collaboration

Use itinerary-level membership as the first collaboration boundary unless requirements explicitly call for persistent groups shared across trips.

A collaboration change should normally include:

1. A membership migration and model relationship.
2. Policies for owner, editor, viewer, admin, and anonymous share access.
3. Invitation handling if users need to join trips.
4. Updated itinerary queries and Blade views.
5. Feature tests for every role and access path.

Do not overload `user_id`, the public share URL, or the admin role to simulate collaboration.

## Known Hazards

Check these before modifying adjacent code:

1. `User` fillable attributes omit `role`, so `DatabaseSeeder` may not create a real admin account.
2. Category UI and routes disagree: the signed-in category page renders write controls, but writes are admin-only.
3. The admin category resource registers `create`, `edit`, and `update`, but `CategoryController` does not implement them.
4. The base users migration already creates `role`; the later role migration has rollback risk.
5. The migration adding `locations.user_id` has an empty `down()` method.
6. Public itinerary share URLs expose sequential itinerary IDs and cannot be revoked.
7. The `verified` dashboard middleware does not enforce verification while `User` does not implement `MustVerifyEmail`.
8. Travel-domain feature tests have not been added yet.

## Change Guidance

- Prefer Laravel conventions: Form Requests for non-trivial validation, policies for authorization, route model binding, Eloquent relationships, and focused feature tests.
- Keep controllers focused on HTTP orchestration. Move reusable business rules out when a feature grows.
- Preserve UTF-8 encoding in Blade and PHP files. Most UI strings are Vietnamese. In Windows PowerShell, use `Get-Content -Encoding utf8` when inspecting them.
- Do not expose or commit `.env`, database files, generated assets, uploaded files, or local credentials.
- Keep `README.md` and files under `docs/` aligned when behavior or setup changes.
- Inspect cascade behavior before changing delete flows.
- Treat share-link changes as security-sensitive.
- Do not introduce an API layer unless the product needs a separate client.

## Commands

Install and run:

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

Verify:

```powershell
composer validate --no-check-publish
npm run build
php artisan route:list --except-vendor
php artisan test
```

The default test suite requires the PHP CLI `pdo_sqlite` extension because `phpunit.xml` uses in-memory SQLite.

- Note: Running tests should be kept at a minimum, kept fast, and report blockers when they cannot run. Do not add or run tests that require a full MySQL or PostgreSQL environment; Update documentation when the related setup, module boundaries, routes, data relationships, or product behavior change.
