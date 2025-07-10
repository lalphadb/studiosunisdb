<?php

return [
    'debug_mode' => env('AUTH_DEBUG', false),
    'require_email_verification' => env('AUTH_REQUIRE_EMAIL_VERIFICATION', false),
    'session_lifetime' => env('SESSION_LIFETIME', 120),
    'multi_tenant' => true,
    'default_role' => 'membre',
    'login_attempts' => 5,
    'lockout_duration' => 60, // minutes
];
