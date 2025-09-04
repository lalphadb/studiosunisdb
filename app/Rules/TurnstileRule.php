<?php

namespace App\Rules;

use App\Services\TurnstileService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TurnstileRule implements ValidationRule
{
    private TurnstileService $turnstileService;
    
    public function __construct()
    {
        $this->turnstileService = app(TurnstileService::class);
    }
    
    /**
     * Validation Cloudflare Turnstile
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Si Turnstile désactivé, passer
        if (!$this->turnstileService->isEnabled()) {
            return;
        }
        
        // Vérifier la réponse
        if (empty($value)) {
            $fail('Veuillez compléter la vérification de sécurité.');
            return;
        }
        
        if (!$this->turnstileService->verify($value)) {
            $fail('La vérification de sécurité a échoué. Veuillez réessayer.');
        }
    }
}
