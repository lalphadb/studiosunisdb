<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Validation personnalisée pour after_time (heures)
        Validator::extend('after_time', function ($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            $heure_debut = $data[$parameters[0]] ?? null;
            
            if (!$heure_debut || !$value) return false;
            
            try {
                $debut = Carbon::createFromFormat('H:i', $heure_debut);
                $fin = Carbon::createFromFormat('H:i', $value);
                return $fin->gt($debut);
            } catch (\Exception $e) {
                return false;
            }
        });

        Validator::replacer('after_time', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':other', $parameters[0], $message);
        });
    }
}
