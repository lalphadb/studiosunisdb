<?php
namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToEcole
{
    public function ecole(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Ecole::class);
    }

    protected static function bootBelongsToEcole(): void
    {
        static::creating(function ($model) {
            if (auth()->check() && empty($model->ecole_id)) {
                $model->ecole_id = auth()->user()->ecole_id ?? 1;
            }
        });
    }
}
