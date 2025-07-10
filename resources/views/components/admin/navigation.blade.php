<nav class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white dark:bg-gray-900 dark:border-gray-700 px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
    <!-- Mobile menu button -->
    <button type="button" 
            class="lg:hidden -m-2.5 p-2.5 text-gray-700 dark:text-gray-300" 
            @click="sidebarOpen = true">
        <span class="sr-only">Ouvrir le menu</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
    </button>

    <!-- Separator -->
    <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 lg:hidden" aria-hidden="true"></div>

    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
        <!-- Search -->
        <form class="relative flex flex-1" action="#" method="GET">
            <label for="search-field" class="sr-only">Rechercher</label>
            <svg class="pointer-events-none absolute inset-y-0 left-0 h-full w-5 text-gray-400 dark:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
            </svg>
            <input id="search-field"
                   class="block h-full w-full border-0 py-0 pl-8 pr-0 text-gray-900 dark:text-gray-100 dark:bg-gray-900 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-0 sm:text-sm"
                   placeholder="Rechercher..."
                   type="search"
                   name="search">
        </form>

        <div class="flex items-center gap-x-4 lg:gap-x-6">
            <!-- Dark mode toggle -->
            <x-dark-mode-toggle />

            <!-- Notifications -->
            <button type="button" class="-m-2.5 p-2.5 text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400">
                <span class="sr-only">Voir les notifications</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
            </button>

            <!-- Separator -->
            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200 dark:bg-gray-700" aria-hidden="true"></div>

            <!-- Profile dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button type="button" 
                        class="-m-1.5 flex items-center p-1.5"
                        @click="open = !open"
                        @click.away="open = false">
                    <span class="sr-only">Ouvrir le menu utilisateur</span>
                    <img class="h-8 w-8 rounded-full bg-gray-50 dark:bg-gray-800" 
                         src="{{ $currentUser->avatar_url ?? asset('images/default-avatar.png') }}" 
                         alt="{{ $currentUser->nom_complet ?? 'Utilisateur' }}">
                    <span class="hidden lg:flex lg:items-center">
                        <span class="ml-4 text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">
                            {{ $currentUser->nom_complet ?? 'Utilisateur' }}
                        </span>
                        <svg class="ml-2 h-5 w-5 text-gray-400 dark:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </button>

                <!-- Dropdown -->
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 z-10 mt-2.5 w-64 origin-top-right rounded-md bg-white dark:bg-gray-800 py-2 shadow-lg ring-1 ring-gray-900/5 dark:ring-gray-100/5 focus:outline-none">
                    
                    <!-- User info -->
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $currentUser->nom_complet ?? 'Utilisateur' }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $currentUser->email ?? '' }}</p>
                        @if($currentEcole)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <i class="fas fa-school mr-1"></i> {{ $currentEcole->nom }}
                            </p>
                        @endif
                    </div>

                    <!-- Menu items -->
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-user-circle mr-2"></i> Mon profil
                    </a>
                    
                    <a href="{{ route('profile.settings') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-cog mr-2"></i> Paramètres
                    </a>

                    @if(auth()->user()->hasRole('superadmin'))
                        <a href="{{ route('admin.telescope.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-satellite mr-2"></i> Telescope
                        </a>
                    @endif

                    <div class="border-t border-gray-200 dark:border-gray-700 mt-2 pt-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
