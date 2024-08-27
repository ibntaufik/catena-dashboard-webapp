<?php
/**
 * User: faisal
 * Date: 06/08/2024
 * Time: 14.34
 */

return [
    "ttl"               => env('CACHE_TTL', 600),// 10 minutes
    "account_status"    => [
        "fc"            => "Field Coordinator",
        "vendor"        => "Vendor",
        "how"           => "Head of Warehouse"
    ],
    "one_signal"        => [
        "url"           => env("ONESIGNAL_URL", "https://api.onesignal.com/notifications?c=push"),
        "token"         => env("ONESIGNAL_TOKEN", "OTczYjgxMmYtZjEzNi00MGE3LTk0N2ItNTM0NWIwMDk1MGI1"),
        "app_id"        => env("ONESIGNAL_APP_ID", "6e35f10b-f0b4-40c4-b7ca-a0b988f38cfc")
    ]
];