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
    ],
    "fabric"            => [
        "channel"       => env("FABRIC_CHANNEL", "varion"),
        "chaincode"     => [
            "farmer_private"    => env("FABRIC_FARMER_PRIVATE_CHAINCODE", "farmer_private"),
            "pulper_private"    => env("FABRIC_PULPER_PRIVATE_CHAINCODE", "pulper_private"),
            "huller_private"    => env("FABRIC_HULLER_PRIVATE_CHAINCODE", "huller_private"),
        ]
    ],
    "api_url"           => env("API_URL"),
    "asset_path"        => [
        "bank_info"     => "bank_info",
        "identity"      => "id_numbers",
        "business_unit" => "photos",
        "farm"          => "land_photos"
    ],
    "supplier_status"   => [
        "invalid"           => "Invalid",
        "non_verified"      => "Non Verified",
        "partial_verified"  => "Partial Verified",
        "verified"          => "Verified"
    ],
];