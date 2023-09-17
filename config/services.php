<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'signnow' => [
        'api_key' => env('SIGNNOW_CLIENT_ID'),
        'api_secret' => env('SIGNNOW_CLIENT_SECRET'),
        'api_url' => env('SIGNNOW_API_BASE_URL'),
        'token' => env('SIGNNOW_TOKEN')
    ],


    'docusign' => [
        'impersonated_user_id' => env('DS_IMPERSONATED_USER_ID'),
        'jwt_scope' => env('DS_JWT_SCOPE'),
        'auth_server' => env('DS_AUTH_SERVER'),
        'esign_uri_suffix' => env('DS_ESIGN_URI_SUFFIX'),
        'key_path' => env('DS_KEY_PATH'),
        'client_id' => env('DS_CLIENT_ID')
    ]

];
