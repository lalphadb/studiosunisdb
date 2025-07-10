{{-- Header Admin Adaptatif --}}
<header class="bg-slate-800 border-b border-slate-700 px-6 py-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold text-white">
                @yield('title', 'Administration')
                @if(auth()->user()->hasRole('superadmin'))
                    <span class="ml-2 px-2 py-1 text-xs bg-purple-500/20 text-purple-400 rounded-full">
                        SUPERADMIN
                    </span>
                @elseif(auth()->user()->hasRole('admin_ecole'))
                    <span class="ml-2 px-2 py-1 text-xs bg-emerald-500/20 text-emerald-400 rounded-full">
                        ADMIN ÉCOLE
                    </span>
                @endif
            </h1>
            @auth
                <p class="text-slate-400 text-sm">
                    {{ auth()->user()->name }}
                    @if(auth()->user()->ecole)
                        - <span class="text-emerald-400">{{ auth()->user()->ecole->nom }}</span>
                    @endif
                    - 
                    @foreach(auth()->user()->roles as $role)
                        <span class="text-blue-400">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                    @endforeach
                </p>
            @endauth
        </div>
        
        {{-- Stats rapides selon rôle --}}
        <div class="hidden md:flex items-center space-x-6">
            @if(auth()->user()->hasRole('superadmin'))
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">{{ \App\Models\Ecole::count() }}</div>
                    <div class="text-xs text-slate-400">Écoles</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">{{ \App\Models\User::count() }}</div>
                    <div class="text-xs text-slate-400">Utilisateurs</div>
                </div>
            @elseif(auth()->user()->hasRole('admin_ecole'))
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">
                        {{ \App\Models\User::where('ecole_id', auth()->user()->ecole_id)->count() }}
                    </div>
                    <div class="text-xs text-slate-400">Membres</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-white">
                        {{ \App\Models\Cours::where('ecole_id', auth()->user()->ecole_id)->where('active', true)->count() }}
                    </div>
                    <div class="text-xs text-slate-400">Cours Actifs</div>
                </div>
            @endif
        </div>
        
        <!-- User Dropdown (existant mais amélioré) -->
        @auth
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" 
                    class="flex items-center space-x-3 bg-slate-700/50 hover:bg-slate-600/50 px-4 py-2 rounded-lg transition-colors border border-slate-600/30">
                <div class="h-8 w-8 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center">
                    <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <div class="text-left">
                    <div class="text-white text-sm font-medium">{{ auth()->user()->name }}</div>
                    <div class="text-slate-400 text-xs">{{ auth()->user()->email }}</div>
                </div>
                <x-admin-icon name="chevron-down" size="w-4 h-4" color="text-slate-400" />
            </button>
            
            <div x-show="open" @click.away="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 bg-slate-700 rounded-lg shadow-lg border border-slate-600 z-50">
                <div class="py-2">
                    <a href="{{ route('admin.users.show', auth()->user()) }}" 
                       class="flex items-center px-4 py-2 text-slate-300 hover:bg-slate-600 hover:text-white transition-colors">
                        <x-admin-icon name="settings" size="w-4 h-4" />
                        <span class="ml-3">Mon Profil</span>
                    </a>
                    
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-4 py-2 text-slate-300 hover:bg-indigo-600 hover:text-white transition-colors">
                        <x-admin-icon name="eye" size="w-4 h-4" />
                        <span class="ml-3">Vue Membre</span>
                    </a>
                    
                    <div class="border-t border-slate-600 my-2"></div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="flex items-center w-full px-4 py-2 text-slate-300 hover:bg-red-600 hover:text-white transition-colors">
                            <x-admin-icon name="logout" size="w-4 h-4" />
                            <span class="ml-3">Déconnexion</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endauth
    </div>
</header>
