<?php

return [
    'account' => env('NETSUITE_ACCOUNT'),
    'consumer_key' => env('NETSUITE_CONSUMER_KEY'),
    'consumer_secret' => env('NETSUITE_CONSUMER_SECRET'),
    'token_id' => env('NETSUITE_TOKEN_ID'),
    'token_secret' => env('NETSUITE_TOKEN_SECRET'),
    'role' => env('NETSUITE_ROLE'),
    'realm' => env('NETSUITE_REALM'),
    'endpoint' => env('NETSUITE_ENDPOINT', 'https://webservices.netsuite.com'),
    'app_id' => env('NETSUITE_APP_ID'),
];
