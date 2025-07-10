<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ===================================================================
// ROUTES PUBLIQUES
// ===================================================================

// Page d'accueil
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('welcome');
})->name('home');

// Pages légales
Route::prefix('legal')->name('legal.')->group(function () {
    Route::view('/privacy', 'legal.privacy')->name('privacy');
    Route::view('/terms', 'legal.terms')->name('terms');
    Route::view('/cookies', 'legal.cookies')->name('cookies');
});

// Page de maintenance
Route::view('/maintenance', 'maintenance')->name('maintenance');

// ===================================================================
// AUTHENTIFICATION (Laravel Breeze)
// ===================================================================

Route::middleware('guest')->group(function () {
    Route::get('register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);

    Route::get('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [\App\Http\Controllers\Auth\NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])
                ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', [\App\Http\Controllers\Auth\EmailVerificationPromptController::class, '__invoke'])
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [\App\Http\Controllers\Auth\VerifyEmailController::class, '__invoke'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [\App\Http\Controllers\Auth\EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [\App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [\App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'store']);

    Route::put('password', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});

// ===================================================================
// ROUTES PROTÉGÉES PAR AUTHENTIFICATION
// ===================================================================

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Redirection après connexion basée sur le rôle
    Route::get('/redirect', function () {
        $user = auth()->user();
        
        if ($user->hasAnyRole(['superadmin', 'admin_ecole', 'instructeur'])) {
            return redirect()->route('admin.dashboard');
        }
        
        return redirect()->route('membre.dashboard');
    })->name('redirect');

    // Toggle Dark Mode (AJAX)
    Route::post('/toggle-dark-mode', function () {
        $darkMode = request()->input('darkMode', false);
        session(['dark-mode' => $darkMode]);
        return response()->json(['success' => true]);
    })->name('toggle-dark-mode');
});

// ===================================================================
// ESPACE MEMBRE
// ===================================================================

Route::middleware(['auth', 'verified'])->prefix('membre')->name('membre.')->group(function () {
    
    // Dashboard membre
    Route::get('/dashboard', function () {
        return view('membre.dashboard');
    })->name('dashboard');
    
    // Mes cours
    Route::get('/cours', function () {
        $user = auth()->user();
        // TODO: Implémenter la logique
        return view('membre.cours.index');
    })->name('cours.index');
    
    // Mes présences
    Route::get('/presences', function () {
        $user = auth()->user();
        // TODO: Implémenter la logique
        return view('membre.presences.index');
    })->name('presences.index');
    
    // Mes paiements
    Route::get('/paiements', function () {
        $user = auth()->user();
        // TODO: Implémenter la logique
        return view('membre.paiements.index');
    })->name('paiements.index');
    
    // Mes ceintures
    Route::get('/ceintures', function () {
        $user = auth()->user();
        // TODO: Implémenter la logique
        return view('membre.ceintures.index');
    })->name('ceintures.index');
});

// ===================================================================
// WEBHOOKS (si nécessaire)
// ===================================================================

Route::prefix('webhooks')->name('webhooks.')->group(function () {
    // Stripe
    Route::post('/stripe', function () {
        // TODO: Logique webhook Stripe
    })->name('stripe')->middleware('api');
    
    // PayPal
    Route::post('/paypal', function () {
        // TODO: Logique webhook PayPal
    })->name('paypal')->middleware('api');
});

// ===================================================================
// API PUBLIQUES (pour vérification certificats, etc.)
// ===================================================================

Route::prefix('api/public')->name('api.public.')->group(function () {
    // Vérification de certificat de ceinture
    Route::get('/verify-certificate/{numero}', function ($numero) {
        // TODO: Implémenter la logique
        return response()->json(['valid' => false, 'message' => 'Fonctionnalité à venir']);
    })->name('verify-certificate');
});

// ===================================================================
// ROUTES DE DÉVELOPPEMENT (uniquement en local)
// ===================================================================

if (app()->environment('local')) {
    // PHP Info
    Route::get('/phpinfo', function () {
        phpinfo();
    })->middleware(['auth']);
    
    // Clear cache
    Route::get('/clear-cache', function () {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        return 'Cache cleared!';
    })->middleware(['auth']);
}

// ===================================================================
// ROUTE DE FALLBACK
// ===================================================================

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// ===================================================================
// INCLURE LES ROUTES ADMIN
// ===================================================================

require __DIR__.'/admin.php';
