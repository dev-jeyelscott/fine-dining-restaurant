<?php

return [
    'seed_user' => [
        'name' => env('ADMIN_USER_NAME', 'Restaurant Admin'),
        'email' => env('ADMIN_USER_EMAIL', 'admin@example.com'),
        'password' => env('ADMIN_USER_PASSWORD'),
    ],
];
