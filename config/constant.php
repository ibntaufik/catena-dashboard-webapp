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
    ]
];