<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

/**
 * Logger d'activité unifié.
 * - Utilise Spatie Activitylog si présent (fonction activity()).
 * - Sinon fallback vers le channel 'info' du logger Laravel.
 */
final class ActivityLogger
{
    /**
     * @param  string       $event       ex. 'membre.created'
     * @param  mixed|null   $subject     Modèle Eloquent ou scalaires; ignoré si null
     * @param  array<mixed> $properties  Contexte additionnel sérialisable
     */
    public static function log(string $event, mixed $subject = null, array $properties = []): void
    {
        $props = Arr::only($properties, array_keys($properties));
        $user  = auth()->user();

        if (function_exists('activity') && class_exists(\Spatie\Activitylog\Models\Activity::class)) {
            activity()
                ->causedBy($user)
                ->performedOn(is_object($subject) ? $subject : null)
                ->withProperties($props)
                ->event($event)
                ->log($event);
            return;
        }

        // Fallback fichier de logs
        Log::info(sprintf('[%s] %s', __CLASS__, $event), [
            'user_id' => $user?->id,
            'subject' => is_object($subject) ? get_class($subject) . ':' . ($subject->id ?? null) : $subject,
            'props'   => $props,
        ]);
    }
}
