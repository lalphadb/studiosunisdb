<aside class="fixed inset-y-0 left-0 z-50 w-64 transform transition-transform duration-300 ease-in-out bg-gray-900 lg:translate-x-0 lg:static lg:inset-0"
       :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
       @click.away="sidebarOpen = false">
    
    <!-- Logo -->
    <div class="flex h-16 items-center justify-between px-6 bg-gray-800">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center">
            <x-application-logo class="h-8 w-auto fill-white" />
            <span class="ml-2 text-xl font-semibold text-white">StudiosDB</span>
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="mt-5 px-2">
        @php
            $menuItems = [
                [
                    'title' => 'Dashboard',
                    'route' => 'admin.dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                    'permission' => 'dashboard.view',
                    'color' => 'blue'
                ],
                [
                    'title' => 'Utilisateurs',
                    'route' => 'admin.users.index',
                    'icon' => 'fas fa-users',
                    'permission' => 'users.view',
                    'color' => 'green'
                ],
                [
                    'title' => 'Écoles',
                    'route' => 'admin.ecoles.index',
                    'icon' => 'fas fa-school',
                    'permission' => 'ecoles.view',
                    'role' => 'superadmin',
                    'color' => 'orange'
                ],
                [
                    'title' => 'Cours',
                    'route' => 'admin.cours.index',
                    'icon' => 'fas fa-chalkboard-teacher',
                    'permission' => 'cours.view',
                    'color' => 'purple'
                ],
                [
                    'title' => 'Ceintures',
                    'route' => 'admin.ceintures.index',
                    'icon' => 'fas fa-award',
                    'permission' => 'ceintures.view',
                    'color' => 'pink'
                ],
                [
                    'title' => 'Présences',
                    'route' => 'admin.presences.index',
                    'icon' => 'fas fa-calendar-check',
                    'permission' => 'presences.view',
                    'color' => 'teal'
                ],
                [
                    'title' => 'Paiements',
                    'route' => 'admin.paiements.index',
                    'icon' => 'fas fa-dollar-sign',
                    'permission' => 'paiements.view',
                    'color' => 'green'
                ],
                [
                    'title' => 'Séminaires',
                    'route' => 'admin.seminaires.index',
                    'icon' => 'fas fa-graduation-cap',
                    'permission' => 'seminaires.view',
                    'color' => 'orange'
                ],
                [
                    'title' => 'Sessions',
                    'route' => 'admin.sessions.index',
                    'icon' => 'fas fa-clock',
                    'permission' => 'sessions.view',
                    'color' => 'cyan'
                ],
                [
                    'title' => 'Exports',
                    'route' => 'admin.exports.index',
                    'icon' => 'fas fa-file-export',
                    'permission' => 'exports.view',
                    'color' => 'indigo'
                ],
                [
                    'title' => 'Logs',
                    'route' => 'admin.logs.index',
                    'icon' => 'fas fa-history',
                    'permission' => 'logs.view',
                    'role' => 'superadmin',
                    'color' => 'gray'
                ],
            ];

            $colorClasses = [
                'blue' => 'hover:bg-blue-800',
                'green' => 'hover:bg-green-800',
                'orange' => 'hover:bg-orange-800',
                'purple' => 'hover:bg-purple-800',
                'pink' => 'hover:bg-pink-800',
                'teal' => 'hover:bg-teal-800',
                'cyan' => 'hover:bg-cyan-800',
                'indigo' => 'hover:bg-indigo-800',
                'gray' => 'hover:bg-gray-800',
            ];
        @endphp

        @foreach($menuItems as $item)
            @if(
                (!isset($item['permission']) || auth()->user()->can($item['permission'])) &&
                (!isset($item['role']) || auth()->user()->hasRole($item['role']))
            )
                <a href="{{ route($item['route']) }}" 
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-300 {{ $colorClasses[$item['color']] ?? 'hover:bg-gray-700' }} hover:text-white mb-1 transition-colors duration-150 {{ request()->routeIs($item['route'] . '*') ? 'bg-gray-800 text-white' : '' }}">
                    <i class="{{ $item['icon'] }} mr-3 h-5 w-5 flex-shrink-0"></i>
                    {{ $item['title'] }}
                    @if(request()->routeIs($item['route'] . '*'))
                        <span class="ml-auto h-2 w-2 bg-{{ $item['color'] }}-500 rounded-full"></span>
                    @endif
                </a>
            @endif
        @endforeach
    </nav>

    <!-- User info -->
    <div class="absolute bottom-0 w-full px-4 py-4 bg-gray-800">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <img class="h-10 w-10 rounded-full" 
                     src="{{ auth()->user()->avatar_url ?? asset('images/default-avatar.png') }}" 
                     alt="{{ auth()->user()->nom_complet }}">
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-white">
                    {{ auth()->user()->nom_complet }}
                </p>
                <p class="text-xs text-gray-400">
                    {{ auth()->user()->roles->first()->display_name ?? 'Utilisateur' }}
                </p>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile backdrop -->
<div x-show="sidebarOpen" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-gray-600 bg-opacity-75 lg:hidden"
     @click="sidebarOpen = false"></div>
