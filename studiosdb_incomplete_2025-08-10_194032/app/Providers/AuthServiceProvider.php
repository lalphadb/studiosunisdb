<?php

namespace App\Providers;

use App\Models\Member;
use App\Models\User;
use App\Policies\MemberPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class   => UserPolicy::class,
        Member::class => MemberPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
