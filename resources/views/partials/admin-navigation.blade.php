<!-- Navigation Admin StudiosDB Enterprise -->
<nav class="bg-slate-800 border-r border-slate-700 w-64 min-h-screen">
    <div class="p-6">
        <!-- Logo StudiosDB -->
        <div class="flex items-center space-x-3 mb-8">
            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-sm">SDB</span>
            </div>
            <div>
                <h1 class="text-white font-bold text-lg">StudiosDB</h1>
                <p class="text-slate-400 text-xs">Enterprise v4.1.10.2</p>
            </div>
        </div>

        <!-- Dashboard Links -->
        <div class="space-y-1">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                <x-admin-icon name="dashboard" size="w-5 h-5" class="mr-3"/>
                <span class="ml-3 font-medium">
                    @if(auth()->user()->hasRole('superadmin'))
                        Dashboard Global
                    @elseif(auth()->user()->hasRole('admin_ecole'))
                        Dashboard École
                    @else
                        Dashboard
                    @endif
                </span>
                @if(auth()->user()->hasRole('superadmin'))
                    <span class="ml-auto px-2 py-1 text-xs bg-emerald-500/20 text-emerald-300 rounded">
                        @elseif(auth()->user()->hasRole('admin_ecole'))
                        École
                    @endif
                </span>
            </a>
        </div>

        <!-- Navigation Menu -->
        <div class="mt-8 space-y-2">
            <!-- Utilisateurs -->
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                <x-admin-icon name="users" size="w-5 h-5" class="mr-3"/>
                <span class="ml-3">Utilisateurs</span>
            </a>

            <!-- Écoles -->
            @if(auth()->user()->hasRole('superadmin'))
            <a href="{{ route('admin.ecoles.index') }}" 
               class="flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                <x-admin-icon name="ecoles" size="w-5 h-5" class="mr-3"/>
                <span class="ml-3">Écoles</span>
            </a>
            @endif

            <!-- Cours -->
            <a href="{{ route('admin.cours.index') }}" 
               class="flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                <x-admin-icon name="cours" size="w-5 h-5" class="mr-3"/>
                <span class="ml-3">Cours</span>
            </a>

            <!-- Ceintures -->
            <a href="{{ route('admin.ceintures.index') }}" 
               class="flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                <x-admin-icon name="ceintures" size="w-5 h-5" class="mr-3"/>
                <span class="ml-3">Ceintures</span>
            </a>

            <!-- Présences -->
            <a href="{{ route('admin.presences.index') }}" 
               class="flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                <x-admin-icon name="presences" size="w-5 h-5" class="mr-3"/>
                <span class="ml-3">Présences</span>
            </a>

            <!-- Paiements -->
            <a href="{{ route('admin.paiements.index') }}" 
               class="flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                <x-admin-icon name="paiements" size="w-5 h-5" class="mr-3"/>
                <span class="ml-3">Paiements</span>
            </a>

            <!-- Séminaires -->
            <a href="{{ route('admin.seminaires.index') }}" 
               class="flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                <x-admin-icon name="seminaires" size="w-5 h-5" class="mr-3"/>
                <span class="ml-3">Séminaires</span>
            </a>
        </div>

        <!-- Footer Navigation -->
        <div class="mt-8 pt-8 border-t border-slate-700">
            <div class="text-slate-400 text-xs mb-4">
                <p>Connecté : {{ auth()->user()->name }}</p>
                <p>{{ auth()->user()->ecole->nom ?? 'Système Global' }}</p>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center px-4 py-2 text-slate-400 hover:text-white transition-colors">
                    <span class="mr-2">🚪</span>
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>
