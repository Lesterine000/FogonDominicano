<?php

return [
    'local_username' => env('ADMIN_USERNAME', 'admin'),
    'local_email' => env('ADMIN_EMAIL', 'admin@fogondominicano.local'),
    'allowed_emails' => array_values(array_filter(array_map(
        static fn (string $email): string => strtolower(trim($email)),
        explode(',', (string) env('ADMIN_ALLOWED_EMAILS', 'lesterdark001@gmail.com,fogondominicano00@gmail.com'))
    ))),
];
