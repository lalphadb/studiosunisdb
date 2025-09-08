<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        // Configuration Turnstile (protection anti-bot)
        $turnstileConfig = [];
        if (class_exists(\App\Services\TurnstileService::class)) {
            try {
                $turnstileConfig = app(\App\Services\TurnstileService::class)->getConfig();
            } catch (\Exception $e) {
                // Si le service n'est pas configuré, on continue sans Turnstile
                $turnstileConfig = ['enabled' => false];
            }
        }

        return [
            'turnstile' => $turnstileConfig,
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => method_exists($user, 'getRoleNames') ? $user->getRoleNames()->toArray() : [],
                ] : null,
            ],
            'app' => [
                'name' => config('app.name', 'StudiosDB v6'),
                'version' => config('app.version', 'dev'),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info' => fn () => $request->session()->get('info'),
            ],
        ];
    }
}
