<?php

// config for Nelson/Sms
return [
    'next' => [
        'test_single_url' => 'https://messaging-service.co.tz/api/sms/v1/test/text/single',
        'test_multiple_url' => 'https://messaging-service.co.tz/api/sms/v1/test/text/multi',
        'base_url' => 'https://messaging-service.co.tz/api/sms/v1/',
        'log_enabled' => true, // Enable/disable DB logging
        'api_key' => env('NEXT_SMS_API_KEY'),
        'username' =>  env('NEXT_SMS_API_USERNAME'),
        'password' =>  env('NEXT_SMS_API_PASSWORD'),
        'sender_id' => env('NEXT_SMS_SENDER_ID', 'N-SMS'),
        'client' => null,
    ],
];
