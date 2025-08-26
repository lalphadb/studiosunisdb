<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    private string $secretKey;
    private string $siteKey;
    private bool $enabled;
    
    public function __construct()
    {
        $this->secretKey = config('services.recaptcha.secret_key');
        $this->siteKey = config('services.recaptcha.site_key');
        $this->enabled = config('services.recaptcha.enabled', true);
    }
    
    /**
     * Vérifie la réponse reCAPTCHA
     */
    public function verify(string $response, ?string $ip = null): bool
    {
        // Si reCAPTCHA désactivé (dev), toujours valider
        if (!$this->enabled) {
            return true;
        }
        
        // Si pas de réponse
        if (empty($response)) {
            Log::warning('reCAPTCHA: Aucune réponse fournie');
            return false;
        }
        
        try {
            $verifyResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $this->secretKey,
                'response' => $response,
                'remoteip' => $ip ?? request()->ip(),
            ]);
            
            if (!$verifyResponse->successful()) {
                Log::error('reCAPTCHA: Erreur de vérification', [
                    'status' => $verifyResponse->status(),
                    'body' => $verifyResponse->body()
                ]);
                return false;
            }
            
            $result = $verifyResponse->json();
            
            // Log pour debug
            if (!($result['success'] ?? false)) {
                Log::warning('reCAPTCHA: Échec de validation', [
                    'errors' => $result['error-codes'] ?? [],
                    'hostname' => $result['hostname'] ?? null,
                ]);
            }
            
            return $result['success'] ?? false;
            
        } catch (\Exception $e) {
            Log::error('reCAPTCHA: Exception lors de la vérification', [
                'error' => $e->getMessage()
            ]);
            
            // En cas d'erreur réseau, on peut choisir de:
            // - Refuser (return false) - plus sécuritaire
            // - Accepter (return true) - meilleure UX
            // Ici on choisit la sécurité
            return false;
        }
    }
    
    /**
     * Retourne la clé publique pour le frontend
     */
    public function getSiteKey(): string
    {
        return $this->siteKey;
    }
    
    /**
     * Vérifie si reCAPTCHA est activé
     */
    public function isEnabled(): bool
    {
        return $this->enabled && !empty($this->secretKey) && !empty($this->siteKey);
    }
}
