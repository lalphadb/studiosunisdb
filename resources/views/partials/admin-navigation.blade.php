<nav class="fixed top-0 left-0 w-64 h-full bg-slate-800 border-r border-slate-700 z-50">
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="p-6 border-b border-slate-700">
            <h2 class="text-xl font-bold text-white">StudiosDB</h2>
            <p class="text-sm text-slate-400">Administration</p>
        </div>

        <!-- Navigation Menu -->
        <div class="flex-1 overflow-y-auto py-4">
            <div class="px-4 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-2 text-slate-300 hover:bg-slate-700 hover:text-white rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : '' }}">
                    <span class="mr-3">📊</span>
                    Dashboard
                </a>

                <!-- Utilisateurs -->
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center px-4 py-2 text-slate-300 hover:bg-slate-700 hover:text-white rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white' : '' }}">
                    <span class="mr-3">👥</span>
                    Utilisateurs
                </a>

                <!-- Cours -->
                <a href="{{ route('admin.cours.index') }}" 
                   class="flex items-center px-4 py-2 text-slate-300 hover:bg-slate-700 hover:text-white rounded-lg transition-colors {{ request()->routeIs('admin.cours.*') ? 'bg-violet-600 text-white' : '' }}">
                    <span class="mr-3">📚</span>
                    Cours
                </a>

                <!-- Ceintures -->
                <a href="{{ route('admin.ceintures.index') }}" 
                   class="flex items-center px-4 py-2 text-slate-300 hover:bg-slate-700 hover:text-white rounded-lg transition-colors {{ request()->routeIs('admin.ceintures.*') ? 'bg-orange-600 text-white' : '' }}">
                    <span class="mr-3">🥋</span>
                    Ceintures
                </a>

                <!-- Présences -->
                <a href="{{ route('admin.presences.index') }}" 
                   class="flex items-center px-4 py-2 text-slate-300 hover:bg-slate-700 hover:text-white rounded-lg transition-colors {{ request()->routeIs('admin.presences.*') ? 'bg-green-600 text-white' : '' }}">
                    <span class="mr-3">✅</span>
                    Présences
                </a>

                @if(auth()->user()->hasRole('superadmin'))
                <!-- Écoles (superadmin seulement) -->
                <a href="{{ route('admin.ecoles.index') }}" 
                   class="flex items-center px-4 py-2 text-slate-300 hover:bg-slate-700 hover:text-white rounded-lg transition-colors {{ request()->routeIs('admin.ecoles.*') ? 'bg-cyan-600 text-white' : '' }}">
                    <span class="mr-3">🏫</span>
                    Écoles
                </a>
                @endif
            </div>
        </div>

        <!-- User Info -->
        <div class="p-4 border-t border-slate-700">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                    <span class="text-sm font-bold text-white">{{ substr(auth()->user()->name, 0, 2) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ auth()->user()->getRoleNames()->first() }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full text-left px-2 py-1 text-sm text-slate-400 hover:text-white transition-colors">
                    🚪 Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>
