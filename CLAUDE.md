# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Repository type

This is a **Laravel package** (`furic/redeem-codes` on Packagist), not a standalone application. It is consumed by a host Laravel app via Composer and auto-discovered through the service provider. There is no local app to `serve`, no test suite, and no build step in this repo — runtime exercise happens inside whatever host app pulls it in.

- Supports PHP `^7.2` and Laravel `~5.8 || ~6.0 || ~7.0 || ~8.0` (see [composer.json](composer.json)). Don't introduce features that require newer PHP/Laravel without bumping these constraints.
- Static analysis is configured via Scrutinizer ([.scrunitizer.yml](.scrunitizer.yml)) — formatting rules there (4-space indent, no closing PHP tag, alphabetised use statements, no trailing whitespace) are the de facto style guide.
- Tests and model factories do not exist yet (called out as a TODO in [README.md](README.md)).

## Common commands

There are no package-local commands — everything runs in the host app after `composer require furic/redeem-codes`:

```bash
# In the consuming Laravel app:
php artisan vendor:publish        # publish views to resources/views/vendor/redeem-codes
php artisan migrate               # creates events, redeem_codes, redeem_code_rewards, redeem_code_histories
```

When iterating locally, symlink or path-repository this package into a host app — there is no way to exercise it standalone.

## Architecture

### Service provider wiring
[src/RedeemCodesServiceProvider.php](src/RedeemCodesServiceProvider.php) is the only entry point. It loads the package's routes, migrations, and views, and publishes views to `resources/views/vendor/redeem-codes` for host-app override. The config publish path is commented out — there is **no** config file yet, despite the README's "configurable reward item types" TODO. Reward types are currently hardcoded in [resources/views/index.blade.php](resources/views/index.blade.php) (Coins=1, Gems=2, Remove Ads=4, Character=7, Energy=10, World=18, Revive=22).

### Two controllers, two surfaces
- **Web console** ([src/Http/Controllers/RedeemCodeController.php](src/Http/Controllers/RedeemCodeController.php)) — resource routes at `/redeem-codes` (declared in [routes/web.php](routes/web.php)). No auth gate; the README flags this as a known TODO.
- **Public API** ([src/Http/Controllers/RedeemController.php](src/Http/Controllers/RedeemController.php)) — single `GET /api/redeem/{code}` endpoint (declared in [routes/api.php](routes/api.php)). Returns `200` with the `RedeemCode` (which appends a `rewards` accessor) on success, `400` with `{ "error": "..." }` on failure.

Both controllers extend the **host app's** `App\Http\Controllers\Controller`, not a Laravel base class. The package will fail to load in any host app that doesn't ship that class.

### Data model: codes belong to events, rewards belong to events too
The non-obvious shape of the schema:

- `Event` is the batch/grouping unit. One `store()` call in the web console creates one `Event` row, then `count` `RedeemCode` rows pointing at it, then a row in `RedeemCodeReward` per `reward_types[]` entry — also pointing at the **event**, not the individual codes.
- Therefore **all codes in a batch share the same reward set**. `RedeemCode::rewards()` walks `event → hasMany(RedeemCodeReward)` ([src/Models/RedeemCode.php:25-32](src/Models/RedeemCode.php#L25-L32)) and falls back to a direct `hasMany` only if the code has no event (legacy/orphan path). The `redeem_code_rewards` table has both `redeem_code_id` and `event_id` foreign keys for this reason — current code populates `event_id`, not `redeem_code_id`.
- `RedeemCode` casts `redeemed` and `reusable` to `boolean` ([src/Models/RedeemCode.php:13](src/Models/RedeemCode.php#L13)). Recent commits (`7f35d5c`, `de0878f`) added these casts specifically so [src/Http/Controllers/RedeemController.php:30](src/Http/Controllers/RedeemController.php#L30) and [:51](src/Http/Controllers/RedeemController.php#L51) can use strict `!== false` / `=== false` comparisons. **Don't remove the casts** without also relaxing those comparisons.
- `RedeemCodeHistory` is written on every successful redeem ([src/Http/Controllers/RedeemController.php:56-60](src/Http/Controllers/RedeemController.php#L56-L60)) capturing IP and user-agent. Nothing reads it yet — README mentions "history log page" as a TODO.

### Code generation
12-char strings (uppercased prefix counts toward the 12) drawn from `23456789ABCDEFGHJKLMNPQRSTUVWXYZ` — i.e. no `0/1/O/I/L` to avoid visual ambiguity ([src/Http/Controllers/RedeemCodeController.php:50-59](src/Http/Controllers/RedeemCodeController.php#L50-L59)). If you change the alphabet or length, update the `string('code', 12)` migration column too.

### Reusable codes
When `reusable=1`, `store()` forces `count=1` ([src/Http/Controllers/RedeemCodeController.php:83-85](src/Http/Controllers/RedeemCodeController.php#L83-L85)) and `redeem()` skips `setRedeemed()` ([src/Http/Controllers/RedeemController.php:51-53](src/Http/Controllers/RedeemController.php#L51-L53)) — so a reusable code has no per-user redemption tracking. The README is explicit: per-user usage enforcement is the **client's** responsibility.

### Event date validation quirk
[src/Http/Controllers/RedeemController.php:36-49](src/Http/Controllers/RedeemController.php#L36-L49) validates `start_at` / `end_at` via Laravel's `before:tomorrow` / `after:yesterday` rules against `$event->toArray()`. The migration creates the columns as `start_at` / `end_at` ([database/migrations/2016_05_12_062021_create_events_table.php](database/migrations/2016_05_12_062021_create_events_table.php)), but `Event::$fillable` lists them as `started_at` / `ended_at` ([src/Models/Event.php:10](src/Models/Event.php#L10)) — that's a real inconsistency, not a typo to "fix" without checking which name the host apps populate.

## Conventions when changing this package

- Edits to [resources/views/index.blade.php](resources/views/index.blade.php) affect both the in-package fallback **and** any host that hasn't republished views. Reward-type changes there must stay in sync with whatever Enum the client uses (numeric `type` is opaque to this package).
- New controller methods must remain compatible with `Furic\RedeemCodes\Http\Controllers\*` being resolved by host-app `App\Http\Controllers\Controller` — don't import Laravel's controller base directly.
- Any change to the `redeemed` / `reusable` cast or comparison style needs to touch [RedeemCode.php](src/Models/RedeemCode.php) and [RedeemController.php](src/Http/Controllers/RedeemController.php) together (see commit history `7f35d5c`, `de0878f`).
- Keep the package free of host-app dependencies (no `App\User`, no app-specific config). The README's Unity client lives in a separate repo (`furic/Unity-Redeem-Codes`).
