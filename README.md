
# Laravel Redeem Codes

[![Packagist](https://img.shields.io/packagist/v/furic/redeem-codes)](https://packagist.org/packages/furic/redeem-codes)
[![Packagist](https://img.shields.io/packagist/dt/furic/redeem-codes)](https://packagist.org/packages/furic/redeem-codes)
[![License](https://img.shields.io/github/license/furic/laravel-redeem-codes)](https://packagist.org/packages/furic/redeem-codes)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/furic/laravel-redeem-codes/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/furic/laravel-redeem-codes/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/furic/laravel-redeem-codes/badges/build.png?b=main)](https://scrutinizer-ci.com/g/furic/laravel-redeem-codes/build-status/main)

> A plug-and-play redeem code management system for [Laravel](https://laravel.com/) applications.

`laravel-redeem-codes` is a lightweight package for managing and redeeming codes in Laravel projects. Originally developed for player compensation and event reward systems at [Sweaty Chair Studio](https://www.sweatychair.com), this package offers both a clean API and a simple web-based console for managing redeem codes.

If you're looking for more flexible implementations, also consider:
- [Laravel Promocodes](https://github.com/zgabievi/laravel-promocodes)
- [Laravel Vouchers](https://github.com/beyondcode/laravel-vouchers)

---

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [Web Console](#web-console)
  - [Redeem Code Parameters](#redeem-code-parameters)
  - [Redeem Validator API](#redeem-validator-api)
  - [Unity Client Integration](#unity-client-integration)
- [TODO](#todo)
- [License](#license)

---

## Installation

Install the package via Composer:

```bash
composer require furic/redeem-codes
```

> Laravel 5.5+ will auto-discover the service provider. For earlier versions, register it manually.

### Manual Setup (for Laravel < 5.5)

Add the service provider to your `config/app.php`:

```php
'providers' => [
    // ...
    Furic\RedeemCodes\RedeemCodesServiceProvider::class,
],
```

---

## Configuration

Publish the package's configuration and migration files:

```bash
php artisan vendor:publish
```

Then run the migration to create the redeem codes table:

```bash
php artisan migrate
```

---

## Usage

### Web Console

Visit your application at `/redeem-codes` to access the web-based admin console.

![Laravel Redeem Codes web console](https://www.richardfu.net/wp-content/uploads/laravel-redeem-codes-console.jpg)

Use this interface to browse, create, and manage redeem codes visually.

---

### Redeem Code Parameters

When creating a redeem code, the following fields are available:

- **Code Prefix**: Optional prefix for the redeem code(s), useful for organizing events (e.g., `EASTER23_`). All codes are 12 characters in total.
- **Reusable**: If enabled, the same code can be used by multiple users. Note: the package does not enforce per-user usage restrictions—you must handle this on the client side.
- **Count**: Number of codes to generate.
- **Description**: Internal notes about the purpose of the code batch.
- **Rewards**: Define one or more reward types with a quantity. Rewards are stored as numeric types and should be interpreted using an Enum or similar mapping on the client side.

> To modify reward types or customize the form, edit the `index.blade.php` file.

---

### Redeem Validator API

The API endpoint:

```
GET /api/redeem/{code}
```

This will return the reward data in JSON format for valid codes (HTTP `200`) or an error response (HTTP `400`) if invalid, already used, or expired.

Explore the API in the [Postman documentation](https://documenter.getpostman.com/view/2560814/TVmV6tm8#ea7b5c97-7ed2-40b9-91d0-a658a6088097).

---

### Unity Client Integration

If you're using Unity, you can connect easily using this open source client:
👉 [Unity-Redeem-Codes Repo](https://github.com/furic/Unity-Redeem-Codes)

---

## TODO

- ✅ Add configurable reward item types via config file.
- 🔒 Add authentication for web console (currently open).
- 🧪 Add tests and model factories.
- 📜 Add history log page (already logged in DB).
- ⚠ Add server-side check for reusable codes (low priority).

---

## License

This package is open-sourced software licensed under the [MIT license](https://github.com/furic/laravel-redeem-codes/blob/main/LICENSE).
