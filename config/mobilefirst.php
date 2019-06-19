<?php

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
    'redirect_methods' => ['HEAD', 'GET'],

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
