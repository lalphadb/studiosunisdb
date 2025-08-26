#!/bin/bash

# StudiosDB - Configuration Cloudflare Turnstile
# Purpose: Configure Turnstile (alternative gratuite à reCAPTCHA)

echo "=== Configuration Cloudflare Turnstile pour StudiosDB ==="
echo ""
echo "🎯 Turnstile est GRATUIT et inclus avec Cloudflare!"
echo "   Pas besoin de compte Google ou de clés reCAPTCHA"
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# 1. Ajouter la configuration dans services.php
echo "1. Configuration Laravel..."

# Vérifier si la config existe déjà
if grep -q "turnstile" config/services.php; then
    echo "   Configuration Turnstile déjà présente"
else
    # Ajouter avant la dernière accolade
    sed -i "/^];/i\\
    'turnstile' => [\\
        'enabled' => env('TURNSTILE_ENABLED', true),\\
        'site_key' => env('TURNSTILE_SITE_KEY'),\\
        'secret_key' => env('TURNSTILE_SECRET_KEY'),\\
        'mode' => env('TURNSTILE_MODE', 'managed'),\\
    ]," config/services.php
    
    echo -e "   ${GREEN}✓${NC} Configuration ajoutée dans services.php"
fi

# 2. Ajouter les variables dans .env.example
echo "2. Variables d'environnement..."

if grep -q "TURNSTILE_ENABLED" .env.example; then
    echo "   Variables Turnstile déjà présentes"
else
    cat >> .env.example << 'EOF'

# Cloudflare Turnstile (Protection anti-bot gratuite)
TURNSTILE_ENABLED=true
TURNSTILE_SITE_KEY=
TURNSTILE_SECRET_KEY=
TURNSTILE_MODE=managed
EOF
    echo -e "   ${GREEN}✓${NC} Variables ajoutées dans .env.example"
fi

# 3. Ajouter les clés de test dans .env
echo "3. Configuration mode développement..."

if grep -q "TURNSTILE_SITE_KEY" .env; then
    echo "   Turnstile déjà configuré dans .env"
else
    cat >> .env << 'EOF'

# Cloudflare Turnstile - CLÉS DE TEST
# Ces clés fonctionnent en dev. Remplacez-les par vos vraies clés en production
TURNSTILE_ENABLED=true
# Clé de test qui valide toujours (pour dev)
TURNSTILE_SITE_KEY=1x00000000000000000000AA
TURNSTILE_SECRET_KEY=1x0000000000000000000000000000000AA
TURNSTILE_MODE=managed
EOF
    echo -e "   ${GREEN}✓${NC} Clés de test ajoutées dans .env"
fi

# 4. Créer le middleware
echo "4. Création du middleware..."

cat > app/Http/Middleware/VerifyTurnstile.php << 'EOF'
<?php

namespace App\Http\Middleware;

use App\Services\TurnstileService;
use Closure;
use Illuminate\Http\Request;

class VerifyTurnstile
{
    protected TurnstileService $turnstile;
    
    public function __construct(TurnstileService $turnstile)
    {
        $this->turnstile = $turnstile;
    }
    
    public function handle(Request $request, Closure $next)
    {
        // Si Turnstile désactivé, passer
        if (!$this->turnstile->isEnabled()) {
            return $next($request);
        }
        
        // Vérifier la présence du token
        $token = $request->input('cf-turnstile-response');
        
        if (!$token) {
            return back()->withErrors([
                'turnstile' => 'Veuillez compléter la vérification de sécurité.'
            ]);
        }
        
        // Vérifier le token
        if (!$this->turnstile->verify($token)) {
            return back()->withErrors([
                'turnstile' => 'La vérification de sécurité a échoué. Veuillez réessayer.'
            ]);
        }
        
        return $next($request);
    }
}
EOF

echo -e "   ${GREEN}✓${NC} Middleware créé"

# 5. Mettre à jour HandleInertiaRequests
echo "5. Configuration Inertia..."

# Vérifier si déjà configuré
if grep -q "turnstile" app/Http/Middleware/HandleInertiaRequests.php; then
    echo "   Turnstile déjà configuré dans Inertia"
else
    # Chercher la fonction share et ajouter turnstile
    sed -i "/return \[/a\\
            'turnstile' => app(\App\Services\TurnstileService::class)->getConfig()," \
        app/Http/Middleware/HandleInertiaRequests.php
    
    echo -e "   ${GREEN}✓${NC} Configuration Inertia ajoutée"
fi

# 6. Créer une page de login avec Turnstile
echo "6. Création de la page de login avec Turnstile..."

cat > resources/js/Pages/Auth/LoginTurnstile.vue << 'EOF'
<template>
  <GuestLayout>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
      <div class="relative max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
          <h1 class="text-5xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent mb-2">
            StudiosDB
          </h1>
          <h2 class="mt-6 text-3xl font-extrabold text-white">
            Connexion sécurisée
          </h2>
          <p class="mt-2 text-sm text-slate-400">
            Protection par Cloudflare Turnstile
          </p>
        </div>
        
        <!-- Form -->
        <div class="bg-slate-900/50 backdrop-blur-xl rounded-2xl shadow-2xl border border-slate-700/50 p-8">
          <form class="space-y-6" @submit.prevent="submit">
            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium text-slate-300">
                Email
              </label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                required
                class="mt-1 block w-full px-4 py-3 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500"
              />
              <p v-if="form.errors.email" class="mt-2 text-sm text-red-400">
                {{ form.errors.email }}
              </p>
            </div>
            
            <!-- Password -->
            <div>
              <label for="password" class="block text-sm font-medium text-slate-300">
                Mot de passe
              </label>
              <input
                id="password"
                v-model="form.password"
                type="password"
                required
                class="mt-1 block w-full px-4 py-3 bg-slate-800/50 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500"
              />
              <p v-if="form.errors.password" class="mt-2 text-sm text-red-400">
                {{ form.errors.password }}
              </p>
            </div>
            
            <!-- Turnstile Widget -->
            <div class="py-3 border-t border-slate-700">
              <TurnstileWidget
                v-model="form['cf-turnstile-response']"
                :theme="'dark'"
                :appearance="'always'"
                @verified="onTurnstileVerified"
                @expired="onTurnstileExpired"
                ref="turnstileRef"
              />
              <p v-if="form.errors['cf-turnstile-response']" class="mt-2 text-sm text-red-400">
                {{ form.errors['cf-turnstile-response'] }}
              </p>
            </div>
            
            <!-- Submit -->
            <button
              type="submit"
              :disabled="form.processing || !form['cf-turnstile-response']"
              class="w-full py-3 px-4 rounded-lg font-medium text-white transition-all"
              :class="{
                'bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700': !form.processing && form['cf-turnstile-response'],
                'bg-slate-700 cursor-not-allowed opacity-50': form.processing || !form['cf-turnstile-response']
              }"
            >
              {{ form.processing ? 'Connexion...' : 'Se connecter' }}
            </button>
          </form>
        </div>
        
        <!-- Footer -->
        <p class="text-center text-xs text-slate-500">
          Protégé par Cloudflare Turnstile - Alternative gratuite à reCAPTCHA
        </p>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import TurnstileWidget from '@/Components/TurnstileWidget.vue'

const form = useForm({
  email: '',
  password: '',
  'cf-turnstile-response': ''
})

const turnstileRef = ref(null)

const submit = () => {
  form.post('/login', {
    onFinish: () => {
      if (turnstileRef.value) {
        turnstileRef.value.reset()
      }
      form['cf-turnstile-response'] = ''
    }
  })
}

const onTurnstileVerified = (token) => {
  console.log('Turnstile vérifié avec succès')
}

const onTurnstileExpired = () => {
  form.errors['cf-turnstile-response'] = 'La vérification a expiré. Veuillez réessayer.'
}
</script>
EOF

echo -e "   ${GREEN}✓${NC} Page de login créée"

# 7. Instructions finales
echo ""
echo "════════════════════════════════════════════════════════════════"
echo -e "${GREEN}✅ Configuration Turnstile terminée!${NC}"
echo "════════════════════════════════════════════════════════════════"
echo ""
echo -e "${BLUE}📋 Pour obtenir vos vraies clés Turnstile:${NC}"
echo ""
echo "1. Connectez-vous à Cloudflare Dashboard:"
echo -e "   ${YELLOW}https://dash.cloudflare.com${NC}"
echo ""
echo "2. Dans le menu gauche, cliquez sur:"
echo -e "   ${YELLOW}Turnstile${NC}"
echo ""
echo "3. Cliquez sur ${YELLOW}\"Add site\"${NC} et configurez:"
echo "   • Site name: 4lb.ca"
echo "   • Domains: 4lb.ca, www.4lb.ca, localhost"
echo "   • Widget Mode: ${GREEN}Managed${NC} (recommandé)"
echo ""
echo "4. Copiez les clés et remplacez dans .env:"
echo "   TURNSTILE_SITE_KEY=votre_cle_site"
echo "   TURNSTILE_SECRET_KEY=votre_cle_secrete"
echo ""
echo -e "${GREEN}🚀 Mode test actuel:${NC}"
echo "   Les clés de test sont déjà configurées."
echo "   L'application fonctionne immédiatement!"
echo ""
echo -e "${BLUE}📦 Prochaines étapes:${NC}"
echo "   1. npm run build"
echo "   2. php artisan config:cache"
echo "   3. Tester sur /login"
echo ""
echo -e "${GREEN}✨ Avantages de Turnstile vs reCAPTCHA:${NC}"
echo "   • Gratuit avec Cloudflare"
echo "   • Pas de compte Google requis"
echo "   • Meilleure performance"
echo "   • GDPR compliant"
echo "   • Moins intrusif pour les utilisateurs"
echo ""
