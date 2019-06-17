<?php

return [

    /**
     * Enable auto switch view by device type.
     * When enabled, the system auto switch view to compatible view (sub-view) by user device type (ex: 'index.blade.php' => 'mobile/index.blade.php'),
     * compatible view will be find on `device_sub_dirs`. If not found, not affect.
     */
    'auto_switch_view_by_device' => false,

    /**
     * An array with key is device type and value is sub dir of it. Using to switch view to compatible view (sub-view) by user device type.
     * It only affect when `auto_switch_view_by_device` enabled.
     */
    'device_sub_dirs' => [
        //'phone' => 'phone', // switch when device is phone.
        //'tablet' => 'tablet', // switch when device is tablet.
        //'android' => 'android', // switch when device os is android.
        //'ios' => 'ios', // switch when device os is ios.
        'mobile' => 'mobile', // switch when device is tablet or phone.
    ],
];
