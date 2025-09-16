<?php

return [
    'base_url' => env('WHAPI_BASE_URL', 'https://gate.whapi.cloud'),
    'token' => env('WHAPI_TOKEN'),
    'channel' => env('WHAPI_CHANNEL'),
    'timeout' => env('WHAPI_TIMEOUT', 30),
];