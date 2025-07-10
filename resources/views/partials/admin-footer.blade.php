{{-- Footer Admin Professionnel --}}
<footer class="bg-slate-800 border-t border-slate-700 px-6 py-4 mt-8">
    <div class="flex flex-col md:flex-flex flex-wrap -mx-2 justify-between items-center space-y-2 md:space-y-0">
        <div class="flex items-center space-x-4">
            <div class="text-slate-400 text-sm">
                © 2025 StudiosDB v4.1.10.2
            </div>
            @if(auth()->user()->ecole)
                <div class="text-slate-400 text-sm">
                    {{ auth()->user()->ecole->nom }}
                </div>
            @endif
        </div>
        
        <div class="flex items-center space-x-4">
            @if(auth()->user()->hasRole('superadmin'))
                <span class="text-xs px-2 py-1 bg-purple-500/20 text-purple-400 rounded-full">
                    Mode Superadmin
                </span>
            @endif
            
            <div class="text-slate-400 text-xs">
                Dernière connexion: {{ auth()->user()->updated_at->diffForHumans() }}
            </div>
            
            <div class="text-slate-400 text-xs">
                Session: {{ Str::limit(session()->getId(), 8) }}
            </div>
        </div>
    </div>
</footer>
