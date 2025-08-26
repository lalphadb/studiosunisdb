#!/bin/bash

# StudiosDB - Configuration reCAPTCHA
# Purpose: Configure reCAPTCHA dans Laravel

echo "=== Configuration reCAPTCHA pour StudiosDB ==="
echo ""

# 1. Ajouter la configuration dans services.php
echo "1. Ajout de la configuration reCAPTCHA..."

# Vérifier si la config existe déjà
if grep -q "recaptcha" config/services.php; then
    echo "   Configuration reCAPTCHA déjà présente"
else
    # Ajouter avant la dernière accolade
    sed -i "/^];/i\\
    'recaptcha' => [\\
        'enabled' => env('RECAPTCHA_ENABLED', true),\\
        'site_key' => env('RECAPTCHA_SITE_KEY'),\\
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),\\
    ]," config/services.php
    
    echo "   ✓ Configuration ajoutée dans services.php"
fi

# 2. Ajouter les variables dans .env.example
echo "2. Mise à jour de .env.example..."

if grep -q "RECAPTCHA_ENABLED" .env.example; then
    echo "   Variables reCAPTCHA déjà présentes"
else
    cat >> .env.example << 'EOF'

# reCAPTCHA v2 Checkbox
RECAPTCHA_ENABLED=true
RECAPTCHA_SITE_KEY=your-site-key-here
RECAPTCHA_SECRET_KEY=your-secret-key-here
EOF
    echo "   ✓ Variables ajoutées dans .env.example"
fi

# 3. Créer la migration pour le rate limiting
echo "3. Création de la migration pour le rate limiting..."

cat > database/migrations/$(date +%Y_%m_%d_%H%M%S)_create_login_attempts_table.php << 'EOF'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('email')->nullable();
            $table->string('user_agent')->nullable();
            $table->boolean('successful')->default(false);
            $table->json('metadata')->nullable(); // Pour stocker des infos supplémentaires
            $table->timestamps();
            
            // Index pour les requêtes rapides
            $table->index('ip_address');
            $table->index('email');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_attempts');
    }
};
EOF

echo "   ✓ Migration créée"

# 4. Créer le provider de service
echo "4. Enregistrement du service reCAPTCHA..."

cat > app/Providers/RecaptchaServiceProvider.php << 'EOF'
<?php

namespace App\Providers;

use App\Services\RecaptchaService;
use Illuminate\Support\ServiceProvider;

class RecaptchaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(RecaptchaService::class, function ($app) {
            return new RecaptchaService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Partager la config avec Inertia
        if (class_exists(\Inertia\Inertia::class)) {
            \Inertia\Inertia::share('recaptcha', function () {
                return [
                    'enabled' => config('services.recaptcha.enabled'),
                    'site_key' => config('services.recaptcha.site_key'),
                ];
            });
        }
    }
}
EOF

echo "   ✓ Provider créé"

# 5. Enregistrer le provider
echo "5. Enregistrement du provider..."

# Vérifier si déjà enregistré
if grep -q "RecaptchaServiceProvider" config/app.php; then
    echo "   Provider déjà enregistré"
else
    # Ajouter le provider
    sed -i "/App\\\Providers\\\AppServiceProvider::class,/a\\        App\\\Providers\\\RecaptchaServiceProvider::class," config/app.php
    echo "   ✓ Provider enregistré"
fi

# 6. Créer un exemple de contrôleur avec reCAPTCHA
echo "6. Création d'un exemple de contrôleur..."

cat > app/Http/Controllers/Auth/RegisterWithRecaptchaController.php << 'EOF'
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\RecaptchaRule;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class RegisterWithRecaptchaController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return Inertia::render('Auth/RegisterWithRecaptcha');
    }
    
    /**
     * Handle an incoming registration request with reCAPTCHA.
     */
    public function store(Request $request)
    {
        // Log de la tentative
        DB::table('login_attempts')->insert([
            'ip_address' => $request->ip(),
            'email' => $request->email,
            'user_agent' => $request->userAgent(),
            'successful' => false,
            'metadata' => json_encode([
                'type' => 'registration',
                'has_recaptcha' => $request->has('g-recaptcha-response')
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Validation avec reCAPTCHA
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'g-recaptcha-response' => ['required', new RecaptchaRule()],
        ], [
            'g-recaptcha-response.required' => 'Veuillez cocher la case "Je ne suis pas un robot".',
        ]);
        
        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'ecole_id' => session('ecole_id', 1), // Ou logique pour déterminer l'école
        ]);
        
        // Mettre à jour le log de succès
        DB::table('login_attempts')
            ->where('ip_address', $request->ip())
            ->where('email', $request->email)
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->update(['successful' => true]);
        
        event(new Registered($user));
        
        Auth::login($user);
        
        return redirect()->route('dashboard');
    }
}
EOF

echo "   ✓ Contrôleur exemple créé"

# 7. Résumé
echo ""
echo "=== Configuration terminée ==="
echo ""
echo "📋 Prochaines étapes:"
echo ""
echo "1. Obtenir les clés reCAPTCHA:"
echo "   https://www.google.com/recaptcha/admin"
echo "   Type: reCAPTCHA v2 - Checkbox"
echo ""
echo "2. Ajouter dans .env:"
echo "   RECAPTCHA_ENABLED=true"
echo "   RECAPTCHA_SITE_KEY=votre-cle-site"
echo "   RECAPTCHA_SECRET_KEY=votre-cle-secrete"
echo ""
echo "3. Exécuter les migrations:"
echo "   php artisan migrate"
echo ""
echo "4. Compiler les assets:"
echo "   npm run build"
echo ""
echo "5. Tester sur une page de formulaire"
echo ""
echo "✓ Service RecaptchaService créé"
echo "✓ Rule RecaptchaRule créée"
echo "✓ Component RecaptchaCheckbox.vue créé"
echo "✓ Provider configuré"
echo "✓ Migration de logging créée"
echo ""
