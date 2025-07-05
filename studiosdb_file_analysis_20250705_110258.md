# 📊 RAPPORT D'ANALYSE FICHIERS STUDIOSDB
Date: $(date)

## 📁 INVENTAIRE COMPLET

### Controllers (app/Http/Controllers/)
## 📁 INVENTAIRE COMPLET

### 🎛️ CONTROLLERS
```
📄 app/Http/Controllers/Admin/CoursHoraireController.php
   └─ Lignes: 312
   └─ extends BaseAdminController
   └─ Method:  public function __construct()
   └─ Method:  public function index(Request $request): View
   └─ Method:  public function create(Request $request): View

📄 app/Http/Controllers/Admin/BaseAdminController.php
   └─ Lignes: 785
   └─ extends Controller
   └─ Method:  public function __construct()
   └─ Method:  $this->middleware(function ($request, $next) {
   └─ Method:  protected function handleException(Exception $e, string $action, array $context = []): RedirectResponse

📄 app/Http/Controllers/Admin/ExportController.php
   └─ Lignes: 70
   └─ extends BaseAdminController
   └─ Method:  public function __construct()
   └─ Method:  public function index()
   └─ Method:  public function exportLogs(Request $request)

📄 app/Http/Controllers/Admin/UserController.php
   └─ Lignes: 299
   └─ extends BaseAdminController
   └─ Method:  public function __construct()
   └─ Method:  public function index(Request $request): View
   └─ Method:  public function create(): View

📄 app/Http/Controllers/Admin/SessionCoursController.php
   └─ Lignes: 154
   └─ extends BaseAdminController
   └─ Method:  public function __construct()
   └─ Method:  public function index(Request $request): View
   └─ Method:  public function create(): View

📄 app/Http/Controllers/Admin/LogController.php
   └─ Lignes: 50
   └─ extends BaseAdminController
   └─ Method:  public function __construct()
   └─ Method:  public function index(Request $request)
   └─ Method:  public function clear(Request $request)

📄 app/Http/Controllers/Admin/RoleController.php
   └─ Lignes: 92
   └─ extends BaseAdminController
   └─ Method:  public function __construct()
   └─ Method:  public function index(): View
   └─ Method:  public function edit(Role $role): View

📄 app/Http/Controllers/Admin/SeminaireController.php
   └─ Lignes: 153
   └─ extends BaseAdminController
   └─ Method:  public function __construct()
   └─ Method:  public function index(Request $request): View
   └─ Method:  public function create(): View

📄 app/Http/Controllers/Admin/CoursController.php
   └─ Lignes: 238
   └─ extends BaseAdminController
   └─ Method:  public function __construct()
   └─ Method:  public function index(Request $request): View
   └─ Method:  public function show(Cours $cours): View

📄 app/Http/Controllers/Admin/InscriptionSeminaireController.php
   └─ Lignes: 93
   └─ extends BaseAdminController
   └─ Method:  public function __construct()
   └─ Method:  public function index(Request $request)
   └─ Method:  public function destroy(InscriptionSeminaire $inscription)

📄 app/Http/Controllers/Admin/TelescopeController.php
   └─ Lignes: 152
   └─ extends BaseAdminController
   └─ Method:  public function __construct()
   └─ Method:  public function stats(): JsonResponse
   └─ Method:  public function clear(): JsonResponse

📄 app/Http/Controllers/Admin/PresenceController.php
   └─ Lignes: 262
   └─ extends BaseAdminController
   └─ Method:  public function __construct()
   └─ Method:  public function index(Request $request): View
   └─ Method:  public function create(): View

📄 app/Http/Controllers/Admin/PaiementController.php
   └─ Lignes: 468
   └─ extends BaseAdminController
   └─ Method:  public function __construct()
   └─ Method:  public function index(Request $request): View
   └─ Method:  $query->where(function ($q) use ($search) {

📄 app/Http/Controllers/Admin/EcoleController.php
   └─ Lignes: 248
   └─ extends BaseAdminController
   └─ Method:  public function __construct()
   └─ Method:  public function index(Request $request): View
   └─ Method:  public function create(): View

📄 app/Http/Controllers/Admin/CeintureController.php
   └─ Lignes: 430
   └─ extends BaseAdminController
   └─ Method:  public function __construct()
   └─ Method:  public function index(Request $request): View
   └─ Method:  public function create(Request $request): View

📄 app/Http/Controllers/Admin/DashboardController.php
   └─ Lignes: 93
   └─ extends BaseAdminController
   └─ Method:  public function __construct()
   └─ Method:  public function index(Request $request): View
   └─ Method:  private function getSuperadminDashboard(): View

📄 app/Http/Controllers/Membre/MembreController.php
   └─ Lignes: 80
   └─ extends Controller
   └─ Method:  public function profil()
   └─ Method:  public function edit()
   └─ Method:  public function update(Request $request)

📄 app/Http/Controllers/ProfileController.php
   └─ Lignes: 60
   └─ extends Controller
   └─ Method:  public function edit(Request $request): View
   └─ Method:  public function update(ProfileUpdateRequest $request): RedirectResponse
   └─ Method:  public function destroy(Request $request): RedirectResponse

📄 app/Http/Controllers/Controller.php
   └─ Lignes: 12
   └─ extends BaseController

📄 app/Http/Controllers/LegalController.php
   └─ Lignes: 43
   └─ extends Controller
   └─ Method:  public function privacy()
   └─ Method:  public function terms()
   └─ Method:  public function contact()

📄 app/Http/Controllers/Auth/PasswordResetLinkController.php
   └─ Lignes: 44
   └─ extends Controller
   └─ Method:  public function create(): View
   └─ Method:  public function store(Request $request): RedirectResponse

📄 app/Http/Controllers/Auth/RegisterController.php
   └─ Lignes: 78
   └─ extends Controller
   └─ Method:  public function create(): View
   └─ Method:  public function store(Request $request): RedirectResponse

📄 app/Http/Controllers/Auth/AuthenticatedSessionController.php
   └─ Lignes: 53
   └─ extends Controller
   └─ Method:  public function create(): View
   └─ Method:  public function store(LoginRequest $request): RedirectResponse
   └─ Method:  public function destroy(Request $request): RedirectResponse

📄 app/Http/Controllers/Auth/RegisteredUserController.php
   └─ Lignes: 50
   └─ extends Controller
   └─ Method:  public function create(): View
   └─ Method:  public function store(Request $request): RedirectResponse

📄 app/Http/Controllers/Auth/PasswordController.php
   └─ Lignes: 29
   └─ extends Controller
   └─ Method:  public function update(Request $request): RedirectResponse

📄 app/Http/Controllers/Auth/EmailVerificationNotificationController.php
   └─ Lignes: 24
   └─ extends Controller
   └─ Method:  public function store(Request $request): RedirectResponse

📄 app/Http/Controllers/Auth/EmailVerificationPromptController.php
   └─ Lignes: 21
   └─ extends Controller
   └─ Method:  public function __invoke(Request $request): RedirectResponse|View

📄 app/Http/Controllers/Auth/NewPasswordController.php
   └─ Lignes: 62
   └─ extends Controller
   └─ Method:  public function create(Request $request): View
   └─ Method:  public function store(Request $request): RedirectResponse
   └─ Method:  function (User $user) use ($request) {

📄 app/Http/Controllers/Auth/VerifyEmailController.php
   └─ Lignes: 27
   └─ extends Controller
   └─ Method:  public function __invoke(EmailVerificationRequest $request): RedirectResponse

📄 app/Http/Controllers/Auth/ConfirmablePasswordController.php
   └─ Lignes: 40
   └─ extends Controller
   └─ Method:  public function show(): View
   └─ Method:  public function store(Request $request): RedirectResponse

```

### 👁️ VUES (resources/views/)
```
📄 resources/views/legal/contact.blade.php
   └─ @extends('layouts.app')

📄 resources/views/legal/terms.blade.php
   └─ @extends('layouts.app')

📄 resources/views/legal/privacy.blade.php
   └─ @extends('layouts.app')

📄 resources/views/auth/confirm-password.blade.php
   └─ Components: 3

📄 resources/views/auth/register.blade.php

📄 resources/views/auth/forgot-password.blade.php
   └─ Components: 5

📄 resources/views/auth/reset-password.blade.php
   └─ Components: 8

📄 resources/views/auth/login.blade.php

📄 resources/views/auth/verify-email.blade.php
   └─ Components: 2

📄 resources/views/components/modal.blade.php

📄 resources/views/components/admin-icon.blade.php

📄 resources/views/components/text-input.blade.php

📄 resources/views/components/admin/flash-messages.blade.php

📄 resources/views/components/actions-dropdown.blade.php
   └─ Components: 3

📄 resources/views/components/primary-button.blade.php

📄 resources/views/components/application-logo.blade.php

📄 resources/views/components/module-header.blade.php

📄 resources/views/components/admin-table.blade.php
   └─ Components: 3

📄 resources/views/components/empty-state.blade.php

📄 resources/views/components/dark-mode-toggle.blade.php

📄 resources/views/components/module-actions.blade.php
   └─ Components: 4

📄 resources/views/components/guest-layout.blade.php

📄 resources/views/components/input-error.blade.php

📄 resources/views/components/input-label.blade.php

📄 resources/views/components/secondary-button.blade.php

📄 resources/views/components/admin-layout.blade.php
   └─ @extends('layouts.admin')

📄 resources/views/components/danger-button.blade.php

📄 resources/views/components/auth-session-status.blade.php

📄 resources/views/profil/index.blade.php
   └─ @extends('layouts.membre')

📄 resources/views/admin/ceintures/create-masse.blade.php
   └─ @extends('layouts.admin')

📄 resources/views/admin/ceintures/index.blade.php
   └─ @extends('layouts.admin')
   └─ Components: 12

📄 resources/views/admin/ceintures/create.blade.php
   └─ @extends('layouts.admin')
   └─ Components: 8

📄 resources/views/admin/components/metric-cards.blade.php

📄 resources/views/admin/exports/users-pdf.blade.php

📄 resources/views/admin/exports/index.blade.php
   └─ Components: 4

📄 resources/views/admin/paiements/validation-rapide.blade.php
   └─ Components: 2

📄 resources/views/admin/paiements/index.blade.php
   └─ @extends('layouts.admin')
   └─ Components: 8

📄 resources/views/admin/paiements/create.blade.php
   └─ @extends('layouts.admin')

📄 resources/views/admin/paiements/actions-masse.blade.php
   └─ Components: 2

📄 resources/views/admin/seminaires/inscriptions.blade.php
   └─ Components: 3

📄 resources/views/admin/seminaires/index.blade.php
   └─ @extends('layouts.admin')
   └─ Components: 7

📄 resources/views/admin/seminaires/inscrire.blade.php
   └─ Components: 2

📄 resources/views/admin/seminaires/create.blade.php
   └─ @extends('layouts.admin')

📄 resources/views/admin/cours/form.blade.php

📄 resources/views/admin/cours/index.blade.php
   └─ @extends('layouts.admin')
   └─ Components: 2

📄 resources/views/admin/cours/edit.blade.php
   └─ @extends('layouts.admin')
   └─ Includes: 1

📄 resources/views/admin/cours/clone.blade.php
   └─ @extends('layouts.admin')

📄 resources/views/admin/cours/duplicate.blade.php
   └─ @extends('layouts.admin')

📄 resources/views/admin/cours/create.blade.php
   └─ @extends('layouts.admin')
   └─ Includes: 1

📄 resources/views/admin/presences/index.blade.php
   └─ @extends('layouts.admin')

📄 resources/views/admin/presences/prise-presence.blade.php
   └─ Components: 3

📄 resources/views/admin/presences/pdf.blade.php

📄 resources/views/admin/presences/create.blade.php
   └─ @extends('layouts.admin')

📄 resources/views/admin/ecoles/show.blade.php
   └─ @extends('layouts.admin')
   └─ Components: 3

📄 resources/views/admin/ecoles/index.blade.php
   └─ @extends('layouts.admin')
   └─ Components: 9

📄 resources/views/admin/ecoles/edit.blade.php
   └─ @extends('layouts.admin')

📄 resources/views/admin/ecoles/create.blade.php
   └─ @extends('layouts.admin')

📄 resources/views/admin/sessions/show.blade.php

📄 resources/views/admin/sessions/index.blade.php
   └─ @extends('layouts.admin')

📄 resources/views/admin/sessions/edit.blade.php

📄 resources/views/admin/sessions/create.blade.php
   └─ @extends('layouts.admin')
   └─ Components: 9

📄 resources/views/admin/partials/telescope-widget.blade.php

📄 resources/views/admin/users/_actions.blade.php

📄 resources/views/admin/users/show.blade.php
   └─ @extends('layouts.admin')
   └─ Components: 8

📄 resources/views/admin/users/index.blade.php
   └─ @extends('layouts.admin')
   └─ Components: 2

📄 resources/views/admin/users/edit.blade.php
   └─ @extends('layouts.admin')

📄 resources/views/admin/users/create.blade.php
   └─ @extends('layouts.admin')

📄 resources/views/admin/logs/index.blade.php
   └─ Components: 1

📄 resources/views/admin/dashboard/index.blade.php
   └─ Components: 5

📄 resources/views/admin/dashboard/admin-ecole.blade.php
   └─ @extends('layouts.admin')
   └─ Components: 1

📄 resources/views/admin/dashboard/superadmin.blade.php
   └─ @extends('layouts.admin')

📄 resources/views/membre/profil-edit.blade.php
   └─ @extends('layouts.membre')

📄 resources/views/membre/profil.blade.php
   └─ @extends('layouts.membre')

📄 resources/views/dashboard.blade.php
   └─ @extends('layouts.membre')

📄 resources/views/errors/403.blade.php
   └─ @extends('layouts.admin')

📄 resources/views/errors/500.blade.php
   └─ @extends('layouts.admin')

📄 resources/views/errors/minimal.blade.php

📄 resources/views/errors/404.blade.php
   └─ @extends('layouts.admin')

📄 resources/views/partials/footer.blade.php

📄 resources/views/partials/admin-navigation.blade.php

📄 resources/views/layouts/guest.blade.php
   └─ Components: 1

📄 resources/views/layouts/admin.blade.php
   └─ Includes: 1

📄 resources/views/layouts/app.blade.php
   └─ Includes: 1

📄 resources/views/layouts/membre.blade.php
   └─ Includes: 1

📄 resources/views/profile/edit.blade.php
   └─ Includes: 3
   └─ Components: 2

📄 resources/views/profile/partials/update-profile-information-form.blade.php
   └─ Components: 7

📄 resources/views/profile/partials/update-password-form.blade.php
   └─ Components: 10

📄 resources/views/profile/partials/delete-user-form.blade.php
   └─ Components: 5

📄 resources/views/welcome.blade.php
   └─ @extends('layouts.app')

```

### 🛣️ ROUTES
```
📄 routes/console.php
   └─ Nombre routes: 0
0

📄 routes/api.php
   └─ Nombre routes: 2
   └─ Route::middleware('auth:sanctum')->get('/user', function (Request $request) {...
   └─ Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {...

📄 routes/admin.php
   └─ Nombre routes: 40
   └─ Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(...
   └─ Route::get('/', [DashboardController::class, 'index'])->name('dashboard');...
   └─ Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard...

📄 routes/web.php
   └─ Nombre routes: 18
   └─ Route::get('/', function () {...
   └─ Route::get('register', [RegisterController::class, 'create'])->name('register');...
   └─ Route::post('register', [RegisterController::class, 'store']);...

📄 routes/auth.php
   └─ Nombre routes: 17
   └─ Route::middleware('guest')->group(function () {...
   └─ Route::get('register', [RegisteredUserController::class, 'create'])...
   └─ Route::post('register', [RegisteredUserController::class, 'store']);...

```
