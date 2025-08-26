<?php

namespace App\Rules;

use App\Services\RecaptchaService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RecaptchaRule implements ValidationRule
{
    private RecaptchaService $recaptchaService;
    
    public function __construct()
    {
        $this->recaptchaService = app(RecaptchaService::class);
    }
    
    /**
     * Validation reCAPTCHA
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Si reCAPTCHA désactivé, passer
        if (!$this->recaptchaService->isEnabled()) {
            return;
        }
        
        // Vérifier la réponse
        if (empty($value)) {
            $fail('Veuillez cocher la case "Je ne suis pas un robot".');
            return;
        }
        
        if (!$this->recaptchaService->verify($value)) {
            $fail('La vérification reCAPTCHA a échoué. Veuillez réessayer.');
        }
    }
}
