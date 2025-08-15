<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class UpdateLastLogin
{
    public function handle(Login $event): void
    {
        $user = $event->user;
        $user->last_login_at = now();
        $user->saveQuietly();
    }
}
