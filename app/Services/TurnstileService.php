<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service Cloudflare Turnstile
 * Alternative gratuite et supérieure à reCAPTCHA
 */
class TurnstileService
{
    private string $secretKey;

    private string $siteKey;

    private bool $enabled;

    private string $mode;

    // URLs de validation Cloudflare
    private const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public function __construct()
    {
        $this->secretKey = config('services.turnstile.secret_key', env('TURNSTILE_SECRET_KEY'));
        $this->siteKey = config('services.turnstile.site_key', env('TURNSTILE_SITE_KEY'));
        $this->enabled = config('services.turnstile.enabled', env('TURNSTILE_ENABLED', true));
        $this->mode = config('services.turnstile.mode', env('TURNSTILE_MODE', 'managed'));
    }

    /**
     * Vérifie la réponse Turnstile
     */
    public function verify(string $response, ?string $ip = null): bool
    {
        // Si Turnstile désactivé (dev), toujours valider
        if (! $this->enabled) {
            Log::info('Turnstile: Désactivé, validation automatique');

            return true;
        }

        // Si pas de réponse
        if (empty($response)) {
            Log::warning('Turnstile: Aucune réponse fournie');

            return false;
        }

        try {
            // Appel API Cloudflare Turnstile
            $verifyResponse = Http::timeout(5)
                ->asForm()
                ->post(self::VERIFY_URL, [
                    'secret' => $this->secretKey,
                    'response' => $response,
                    'remoteip' => $ip ?? request()->ip(),
                ]);

            if (! $verifyResponse->successful()) {
                Log::error('Turnstile: Erreur HTTP', [
                    'status' => $verifyResponse->status(),
                    'body' => $verifyResponse->body(),
                ]);

                return false;
            }

            $result = $verifyResponse->json();

            // Log détaillé pour debug
            if (! ($result['success'] ?? false)) {
                Log::warning('Turnstile: Échec de validation', [
                    'error-codes' => $result['error-codes'] ?? [],
                    'hostname' => $result['hostname'] ?? null,
                    'action' => $result['action'] ?? null,
                    'cdata' => $result['cdata'] ?? null,
                ]);
            } else {
                Log::info('Turnstile: Validation réussie', [
                    'hostname' => $result['hostname'] ?? null,
                    'action' => $result['action'] ?? null,
                    'score' => $result['challenge_ts'] ?? null,
                ]);
            }

            return $result['success'] ?? false;

        } catch (\Exception $e) {
            Log::error('Turnstile: Exception lors de la vérification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // En production, on refuse par sécurité
            // En dev, on peut accepter pour faciliter
            return app()->environment('local', 'development');
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
     * Retourne le mode configuré
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * Vérifie si Turnstile est activé et configuré
     */
    public function isEnabled(): bool
    {
        return $this->enabled && ! empty($this->secretKey) && ! empty($this->siteKey);
    }

    /**
     * Vérifie si on utilise les clés de test
     */
    public function isTestMode(): bool
    {
        // Les clés de test Cloudflare commencent par 1x, 2x ou 3x
        return str_starts_with($this->siteKey, '1x') ||
               str_starts_with($this->siteKey, '2x') ||
               str_starts_with($this->siteKey, '3x');
    }

    /**
     * Retourne la configuration pour le frontend
     */
    public function getConfig(): array
    {
        return [
            'enabled' => $this->isEnabled(),
            'site_key' => $this->siteKey,
            'mode' => $this->mode,
            'test_mode' => $this->isTestMode(),
        ];
    }
}
