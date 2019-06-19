<p align="center">
    <a href="https://github.com/laravel" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/958072" height="100px">
    </a>
    <h1 align="center">Laravel Mobile First</h1>
    <br>
    <p align="center">
    <a href="https://packagist.org/packages/vxm/laravel-mobile-first"><img src="https://img.shields.io/packagist/v/vxm/laravel-mobile-first.svg?style=flat-square" alt="Latest version"></a>
    <a href="https://travis-ci.org/vuongxuongminh/laravel-mobile-first"><img src="https://img.shields.io/travis/vuongxuongminh/laravel-mobile-first/master.svg?style=flat-square" alt="Build status"></a>
    <a href="https://scrutinizer-ci.com/g/vuongxuongminh/laravel-mobile-first"><img src="https://img.shields.io/scrutinizer/g/vuongxuongminh/laravel-mobile-first.svg?style=flat-square" alt="Quantity score"></a>
    <a href="https://styleci.io/repos/192138651"><img src="https://styleci.io/repos/192138651/shield?branch=master" alt="StyleCI"></a>
    <a href="https://packagist.org/packages/vxm/laravel-mobile-first"><img src="https://img.shields.io/packagist/dt/vxm/laravel-mobile-first.svg?style=flat-square" alt="Total download"></a>
    <a href="https://packagist.org/packages/vxm/laravel-mobile-first"><img src="https://img.shields.io/packagist/l/vxm/laravel-mobile-first.svg?style=flat-square" alt="License"></a>
    </p>
</p>

## About it

A package support you implementing mobile-first principle base on [Jenssegers Agent](https://github.com/jenssegers/agent).

## Installation

Require Laravel Mobile First using [Composer](https://getcomposer.org):

```bash
composer require vxm/laravel-mobile-first
```

After require, you need to publish the config-file with:

```php
php artisan vendor:publish --provider="VXM\MobileFirst\MobileFirstServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
    /**
     * Your mobile site use to redirect when user not using desktop device.
     * Note: it only affect when you registered `VXM\MobileFirst\MobileRedirect` middleware.
     */
    'mobile_url' => 'https://m.yoursite.com',

    /**
     * Keep url path when redirect to mobile url (ex: https://yoursite.com/abc to https://m.yoursite.com/abc).
     */
    'keep_url_path' => true,

    /**
     * HTTP status code will be set when redirect to mobile url.
     */
    'redirect_status_code' => 302,

    /**
     * HTTP request method should be redirect to mobile url.
     */
    'redirect_methods' => ['OPTIONS', 'GET'],

    /**
     * Enable auto switch view by device type.
     * When enabled, the system auto switch view to compatible view (sub-view) by user device type (ex: 'index.blade.php' => 'mobile/index.blade.php'),
     * compatible view will be find on `device_sub_dirs`. If not found, not affect.
     */
    'auto_switch_view_by_device' => false,

    /**
     * An array with key is device type and value is sub dir of it. Use to switch view to compatible view (sub-view) by user device type.
     */
    'device_sub_dirs' => [
        //'phone' => 'phone', // switch when device is phone.
        //'tablet' => 'tablet', // switch when device is tablet.
        //'android' => 'android', // switch when device os is android.
        //'ios' => 'ios', // switch when device os is ios.
        'mobile' => 'mobile', // switch when device is tablet or phone.
    ],
];
```

## Usage

This package provides you two features:

+ [Redirect end-user to mobile site url if they not using desktop device.](#mobile-redirect)
+ [Auto switch view to compatible view (sub-view)](#auto-switch-view)

### Mobile redirect

You need setup your mobile site url in your `mobilefirst` config file:

```php
    'mobile_url' => 'https://m.yoursite.com',
```

Now add middleware:

```php
// app/Http/Kernel.php

protected $middleware = [
       ...
       \VXM\MobileFirst\MobileRedirect::class,
],
```

### Auto switch view

This feature is disabled by default, you need to enabled it in `mobilefirst` config file:

```php
    'auto_switch_view_by_device' => true,
```

It is a way to replace a set of views with another by user device without the need of touching the original view rendering code. 
You can use it to systematically change the look and feel of an application depend on user device. 
For example, when call `view('about')`, you will be rendering the view file `resources/views/about.blade.php`, if user use mobile device, the view file `resources/views/mobile/about.blade.php` will be rendered, instead.

The `device_sub_dirs` governs how view files should be replaced by user device. 
It takes an array of key-value pairs, where the keys are the device types and the values are the corresponding view sub-directory. 
The replacement is based on user device: if user device match with any key in the `device_sub_dirs` array, a view path will be added with the corresponding sub-directory value. 
Using the above configuration example, when user using mobile device because it match the key mobile, a view path will be added mobile look like `resources/views/mobile/about.blade.php`. 
Of course you can change the value or add more cases:

```php
    'device_sub_dirs' => [
        'ios' => 'apple', // switch when device os is ios.
        'mobile' => 'mobile', // switch when device is tablet or phone.
    ],
```

The above configuration if user using ios, the view path will be added `apple`.
