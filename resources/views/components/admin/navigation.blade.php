<!-- Sidebar Navigation StudiosDB -->
<aside class="w-64 bg-slate-800 border-r border-slate-700 flex flex-col h-screen fixed left-0 top-0 z-40">
    <!-- Header Sidebar -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-700 p-6 border-b border-slate-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-database text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-white">StudiosDB</h1>
                <p class="text-blue-200 text-xs">Administration v4.1.10.2</p>
            </div>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        @php
            $currentRoute = request()->route()->getName();
        @endphp
        
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-chart-pie w-5 text-center"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <!-- Utilisateurs -->
        @can('users.view')
        <a href="{{ route('admin.users.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.users') ? 'bg-cyan-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-users w-5 text-center"></i>
            <span class="font-medium">Utilisateurs</span>
        </a>
        @endcan

        <!-- Cours -->
        @can('cours.view')
        <a href="{{ route('admin.cours.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.cours') ? 'bg-violet-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-book-open w-5 text-center"></i>
            <span class="font-medium">Cours</span>
        </a>
        @endcan

        <!-- Sessions -->
        @can('sessions.view')
        <a href="{{ route('admin.sessions.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.sessions') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-calendar-alt w-5 text-center"></i>
            <span class="font-medium">Sessions</span>
        </a>
        @endcan

        <!-- Ceintures -->
        @can('ceintures.view')
        <a href="{{ route('admin.ceintures.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.ceintures') ? 'bg-orange-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-medal w-5 text-center"></i>
            <span class="font-medium">Ceintures</span>
        </a>
        @endcan

        <!-- Présences -->
        @can('presences.view')
        <a href="{{ route('admin.presences.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.presences') ? 'bg-green-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-check-circle w-5 text-center"></i>
            <span class="font-medium">Présences</span>
        </a>
        @endcan

        <!-- Séminaires -->
        @can('seminaires.view')
        <a href="{{ route('admin.seminaires.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.seminaires') ? 'bg-pink-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-graduation-cap w-5 text-center"></i>
            <span class="font-medium">Séminaires</span>
        </a>
        @endcan

        <!-- Paiements -->
        @can('paiements.view')
        <a href="{{ route('admin.paiements.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.paiements') ? 'bg-yellow-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-credit-card w-5 text-center"></i>
            <span class="font-medium">Paiements</span>
        </a>
        @endcan

        <!-- Écoles (superadmin seulement) -->
        @if(auth()->user()->hasRole('superadmin'))
        <a href="{{ route('admin.ecoles.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.ecoles') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-school w-5 text-center"></i>
            <span class="font-medium">Écoles</span>
        </a>
        @endif
        
        <!-- Divider -->
        <div class="border-t border-slate-700 my-4"></div>
        
        <!-- Section Système -->
        <div class="space-y-1">
            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-4 py-2">SYSTÈME</h3>
            
            <a href="{{ route('admin.logs.index') }}" 
               class="flex items-center space-x-3 px-4 py-2 rounded-lg text-slate-400 hover:bg-slate-700 hover:text-white transition-all duration-200">
                <i class="fas fa-history w-5 text-center"></i>
                <span class="text-sm">Logs d'activité</span>
            </a>
            
            @if(auth()->user()->hasRole('superadmin'))
                <a href="{{ route('admin.exports.index') }}" 
                   class="flex items-center space-x-3 px-4 py-2 rounded-lg text-slate-400 hover:bg-slate-700 hover:text-white transition-all duration-200">
                    <i class="fas fa-download w-5 text-center"></i>
                    <span class="text-sm">Exports</span>
                </a>
            @endif
        </div>
    </nav>

    <!-- Footer Sidebar -->
    <div class="p-4 border-t border-slate-700">
        <div class="flex items-center justify-between text-slate-400 text-sm">
            <span>{{ auth()->user()->name ?? 'Utilisateur' }}</span>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="hover:text-white transition-colors">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
