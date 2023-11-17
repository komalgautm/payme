<?php

return [
    'api_keys' => [
       
        'client_id' =>  env('PAYME_CLIENT_ID'),
        'client_secret' =>  env('PAYME_CLIENT_SECRET'),
        'signing_key_id' =>  env('PAYME_SINGING_KEY_ID'),
        'signing_key' =>  env('PAYME_SINGING_KEY'),
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'access_token' => env('AUTHORIZATION'),
    ]
];