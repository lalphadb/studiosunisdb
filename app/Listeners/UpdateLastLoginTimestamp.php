<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class UpdateLastLoginTimestamp
{
    public function handle(Login $event): void
    {
        if ($event->user && $event->user->isFillable('last_login_at')) {
            $event->user->forceFill(['last_login_at' => now()])->saveQuietly();
        }
    }
}
