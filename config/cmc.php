<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Panel Allowed IPs / CIDRs
    |--------------------------------------------------------------------------
    | Comma-separated list of IPv4 addresses or CIDR subnets allowed to access
    | /admin. Leave empty to allow any IP (recommended during local dev).
    */
    'admin_allowed_ips' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('ADMIN_ALLOWED_IPS', '')),
    ))),

    /*
    |--------------------------------------------------------------------------
    | Static Export Mode
    |--------------------------------------------------------------------------
    | Set dynamically during `portal:export-static` builds to adapt routes
    | (such as hiding /admin and replacing live POST inquiry forms).
    */
    'static_export' => (bool) env('CMC_STATIC_EXPORT', false),
];
