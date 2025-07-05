<!-- Sidebar fixe -->
<aside class="w-64 bg-slate-800 border-r border-slate-700 flex flex-col h-screen">
    <!-- Header Sidebar -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-700 p-6 border-b border-slate-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-database text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-white">StudiosDB</h1>
                <p class="text-blue-200 text-xs">Administration</p>
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
           class="flex items-center space-x-3 px-4 py-3 rounded-lg border-l-4 transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.dashboard') ? 'bg-blue-600 text-white border-blue-500' : 'text-slate-300 hover:bg-slate-700 hover:text-white border-transparent' }}">
            <i class="fas fa-chart-pie w-5 text-center"></i>
            <span class="font-medium">Dashboard</span>
            @if(str_starts_with($currentRoute, 'admin.dashboard'))
                <div class="ml-auto">
                    <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                </div>
            @endif
        </a>

        <!-- Utilisateurs -->
        @can('users.view')
        <a href="{{ route('admin.users.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg border-l-4 transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.users') ? 'bg-cyan-600 text-white border-cyan-500' : 'text-slate-300 hover:bg-slate-700 hover:text-white border-transparent' }}">
            <i class="fas fa-users w-5 text-center"></i>
            <span class="font-medium">Utilisateurs</span>
            @if(str_starts_with($currentRoute, 'admin.users'))
                <div class="ml-auto">
                    <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                </div>
            @endif
        </a>
        @endcan

        <!-- Cours -->
        @can('cours.view')
        <a href="{{ route('admin.cours.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg border-l-4 transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.cours') ? 'bg-violet-600 text-white border-violet-500' : 'text-slate-300 hover:bg-slate-700 hover:text-white border-transparent' }}">
            <i class="fas fa-book-open w-5 text-center"></i>
            <span class="font-medium">Cours</span>
            @if(str_starts_with($currentRoute, 'admin.cours'))
                <div class="ml-auto">
                    <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                </div>
            @endif
        </a>
        @endcan

        <!-- Sessions -->
        @can('sessions.view')
        <a href="{{ route('admin.sessions.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg border-l-4 transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.sessions') ? 'bg-indigo-600 text-white border-indigo-500' : 'text-slate-300 hover:bg-slate-700 hover:text-white border-transparent' }}">
            <i class="fas fa-calendar-alt w-5 text-center"></i>
            <span class="font-medium">Sessions</span>
            @if(str_starts_with($currentRoute, 'admin.sessions'))
                <div class="ml-auto">
                    <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                </div>
            @endif
        </a>
        @endcan

        <!-- Ceintures -->
        @can('ceintures.view')
        <a href="{{ route('admin.ceintures.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg border-l-4 transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.ceintures') ? 'bg-orange-600 text-white border-orange-500' : 'text-slate-300 hover:bg-slate-700 hover:text-white border-transparent' }}">
            <i class="fas fa-medal w-5 text-center"></i>
            <span class="font-medium">Ceintures</span>
            @if(str_starts_with($currentRoute, 'admin.ceintures'))
                <div class="ml-auto">
                    <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                </div>
            @endif
        </a>
        @endcan

        <!-- Présences -->
        @can('presences.view')
        <a href="{{ route('admin.presences.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg border-l-4 transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.presences') ? 'bg-green-600 text-white border-green-500' : 'text-slate-300 hover:bg-slate-700 hover:text-white border-transparent' }}">
            <i class="fas fa-check-circle w-5 text-center"></i>
            <span class="font-medium">Présences</span>
            @if(str_starts_with($currentRoute, 'admin.presences'))
                <div class="ml-auto">
                    <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                </div>
            @endif
        </a>
        @endcan

        <!-- Séminaires -->
        @can('seminaires.view')
        <a href="{{ route('admin.seminaires.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg border-l-4 transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.seminaires') ? 'bg-pink-600 text-white border-pink-500' : 'text-slate-300 hover:bg-slate-700 hover:text-white border-transparent' }}">
            <i class="fas fa-graduation-cap w-5 text-center"></i>
            <span class="font-medium">Séminaires</span>
            @if(str_starts_with($currentRoute, 'admin.seminaires'))
                <div class="ml-auto">
                    <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                </div>
            @endif
        </a>
        @endcan

        <!-- Paiements -->
        @can('paiements.view')
        <a href="{{ route('admin.paiements.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg border-l-4 transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.paiements') ? 'bg-yellow-600 text-white border-yellow-500' : 'text-slate-300 hover:bg-slate-700 hover:text-white border-transparent' }}">
            <i class="fas fa-credit-card w-5 text-center"></i>
            <span class="font-medium">Paiements</span>
            @if(str_starts_with($currentRoute, 'admin.paiements'))
                <div class="ml-auto">
                    <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                </div>
            @endif
        </a>
        @endcan

        <!-- Écoles (superadmin seulement) -->
        @if(auth()->user()->hasRole('superadmin'))
        <a href="{{ route('admin.ecoles.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg border-l-4 transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.ecoles') ? 'bg-emerald-600 text-white border-emerald-500' : 'text-slate-300 hover:bg-slate-700 hover:text-white border-transparent' }}">
            <i class="fas fa-school w-5 text-center"></i>
            <span class="font-medium">Écoles</span>
            @if(str_starts_with($currentRoute, 'admin.ecoles'))
                <div class="ml-auto">
                    <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                </div>
            @endif
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
</aside>
