@props(['navData'])

<div class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-800/50 backdrop-blur-xl border-r border-slate-700/30">
    <!-- Logo StudiosDB -->
    <div class="flex items-center justify-center h-16 px-6 border-b border-slate-700/30">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-sm">S</span>
            </div>
            <div>
                <h1 class="text-xl font-bold text-white">StudiosDB</h1>
                <p class="text-xs text-slate-400">v4.1.10.2</p>
            </div>
        </div>
    </div>
    
    <!-- Navigation -->
    @include('partials.admin-navigation')
</div>
