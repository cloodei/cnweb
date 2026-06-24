# AGENTS.md

## Mission

This repository is a Laravel travel-planning application. Its implemented baseline is now group-based trip planning: a shared destination catalog, private group workspaces, group-owned itineraries, invite links, PDF export, and basic admin moderation.

Read `docs/PRODUCT.md` before changing collaboration, invite, or itinerary access behavior.

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
| Categories | `CategoryController`, `Category`, admin category view | Internal catalog metadata; regular category pages redirect to locations; admin create/delete; deletion blocked while locations exist |
| Locations | `LocationController`, `Location`, location views | Shared catalog; contributor or admin edit/delete; optional map-assisted create/edit |
| Groups | `GroupController`, `Group`, group views | Private workspace; owner manages group settings and invite links |
| Group destinations | `GroupLocationController`, `GroupLocation`, group destination views | Private to one group; owner/editor create/edit/delete |
| Group invites | `GroupInviteController`, `GroupInvite`, `GroupInviteAcceptance`, group invite views | Authenticated join links with expiry and max-use limits |
| Itineraries | `ItineraryController`, `Itinerary`, itinerary views | Belongs to one group; owner/editor mutate, viewer reads |
| Scheduled stops | `itinerary_location` migration, `ScheduledStop`, `Itinerary::scheduledStops()` | Stop data stores source destination, visit time, and note |
| Admin moderation | `AdminController`, `AdminMiddleware`, admin views | `User::isAdmin()` / `role === 'admin'` |

## Product Invariants

- A `Location` is shared catalog content.
- A `GroupLocation` / group destination is private catalog content for one group.
- A `Group` is the private planning workspace.
- A `GroupInvite` grants authenticated group membership while valid. It is not a public itinerary page.
- An `Itinerary` is a trip plan inside one group.
- `itinerary_location` is the scheduled-stop relation; keep trip-specific time and note data there. It can point to either a shared `Location` or a private `GroupLocation`.
- Itinerary visibility and mutation are controlled by group membership.
- Group owners manage invite links. Editors can mutate itineraries. Viewers can read.
- Location mutation is limited to the contributor or an admin.
- Category metadata is not part of the regular user-facing destination display. Category deletion is blocked by the controller while locations exist. The schema still cascades if a category is deleted at a lower level, so treat it as a high-impact operation.
- Admin moderation is separate from group ownership and group membership.

## Collaboration Guidance

Use groups as the collaboration boundary. Do not reintroduce public itinerary pages as a substitute for group membership.

A collaboration change should normally include:

1. Group or membership model changes when roles or lifecycle change.
2. Policies for owner, editor, viewer, admin moderation, and guest invite access.
3. Invitation handling when users need to join groups.
4. Updated itinerary queries and Blade views.
5. Feature tests for every role and access path.

Do not overload `itineraries.user_id`, the invite URL, or the admin role to simulate collaboration.

## Known Hazards

Check these before modifying adjacent code:

1. Group invite links are join grants. Treat duration, use limits, token storage, and revocation as security-sensitive.
2. `itineraries.user_id` is creator attribution, not the access boundary.
3. Authorization uses policies for groups and itineraries. Keep new access rules there.
4. The base users migration owns `role`; the later role migration is intentionally a no-op compatibility migration.
5. Category deletion is blocked in HTTP flows when locations exist, but direct database deletion can still cascade.
6. Travel-domain feature tests are focused, not complete. PDF export and admin moderation still need coverage.
7. The default PHPUnit suite requires the PHP CLI `pdo_sqlite` extension.

## Change Guidance

- Prefer Laravel conventions: Form Requests for non-trivial validation, policies for authorization, route model binding, Eloquent relationships, and focused feature tests.
- Keep controllers focused on HTTP orchestration. Move reusable business rules out when a feature grows.
- Preserve UTF-8 encoding in Blade and PHP files. Most UI strings are Vietnamese. In Windows PowerShell, use `Get-Content -Encoding utf8` when inspecting them.
- Do not expose or commit `.env`, database files, generated assets, uploaded files, or local credentials.
- Keep `README.md` and files under `docs/` aligned when behavior or setup changes.
- Keep map-assisted place selection progressive: forms must still work without a Google Maps browser key.
- Inspect cascade behavior before changing delete flows.
- Treat invite-link changes as security-sensitive.
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

Do not add or run tests that require a full MySQL or PostgreSQL environment unless the feature specifically depends on that driver. Update documentation when setup, module boundaries, routes, data relationships, or product behavior change.
