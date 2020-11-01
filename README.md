# laravel-redeem-codes

[![Packagist](https://img.shields.io/packagist/v/furic/redeem-codes.svg)](https://packagist.org/packages/furic/redeem-codes)
[![Packagist](https://img.shields.io/packagist/dt/furic/redeem-codes.svg)](https://packagist.org/packages/furic/redeem-codes)
[![License](https://img.shields.io/github/license/furic/laravel-redeem-codes)](https://packagist.org/packages/furic/redeem-codes)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/furic/laravel-redeem-codes/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/furic/laravel-redeem-codes/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/furic/laravel-redeem-codes/badges/build.png?b=main)](https://scrutinizer-ci.com/g/furic/laravel-redeem-codes/build-status/main)

> Redeem code generator and redeemer for [Laravel 5.*](https://laravel.com/). I developed this package while working for a redeem code solution in [Sweaty Chair Studio](https://www.sweatychair.com) catering for compensations and event rewards for players. This contains API for redeem code validation and a simple web console to create and edit the redeem codes. This package is aimed to be a plug-n-play solution, I would also recommend [Laravel Promocodes](https://github.com/zgabievi/laravel-promocodes) and [Laravel Vouchers](https://github.com/beyondcode/laravel-vouchers) that gives you more freedom and being able to build from scratch.

## Table of Contents
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
    - [Web Console](#web-console)
    - [Redeem Code Parameters](#redeem-code-parameters)
    - [Redeem Validator API](#redeem-validator-api)
    - [Unity Client Repo](#unity-client-repo)
- [TODO](#todo)
- [License](#license)

## Installation

Install this package via Composer:
```bash
$ composer require furic/redeem-codes
```

> If you are using Laravel 5.5 or later, then installation is done. Otherwise follow the next steps.

#### Open `config/app.php` and follow steps below:

Find the `providers` array and add our service provider.

```php
'providers' => [
    // ...
    Furic\RedeemCodes\RedeemCodesServiceProvider::class
],
```

## Configuration

Publish config & migration file using Artisan command:
```bash
$ php artisan vendor:publish
```

To create table for redeem codes in database run:
```bash
$ php artisan migrate
```

## Usage

### Web Console

After installation, you can simply browse to `<your server url>`/redeem-codes to open the web console:

![Laravel Redeem Codes web console](https://www.richardfu.net/wp-content/uploads/laravel-redeem-codes-console.jpg)

Here you can browse, create and edit the redeem codes.

### Redeem Code Parameters

- Code Prefix: Set the prefix of the redeem code(s), used for being easier to distingush or with event name. Note that all redeem codes has fixed 12 characters length.
- Reusable: Set if the redeem code(s) is reusable for different players. Note that the server doesn't check if the same user redeeming multiple times, you should add a check to prevent this in your client app.
- Count: Number of redeem code(s) to create.
- Description: The description of the redeem code(s).
- Rewards: The reward type and count, you can add multiple rewards here. The reward type simply is a tiny int and should be convert to Enum in your client app. To add change/add reward type, simply edit the [index.blade.php](src/views/index.blade.php) file.

### Redeem Validator API

Use this API to validate the redeem code: `<your server url>`/redeem. For valide redeem codes, 200 status code is returned with the redeem code JSON data. Otherwise, 400 is returned with the error.

### Unity Client Repo
You can simply import this repo in Unity to communicate with your Laravel server with this package:
`<to be added>`

## TODO

- Finish up the redeem code edit page.
- Add admin login for web console.
- Add tests.
- Add redeem history page (already in database).
- Add reusable server check (low priority).

## License

laravel-redeem-codes is licensed under a [MIT License](https://github.com/furic/laravel-redeem-codes/blob/main/LICENSE).
