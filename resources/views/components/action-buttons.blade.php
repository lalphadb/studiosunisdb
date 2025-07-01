@props(['actions'])

<div class="flex flex-wrap gap-2 items-center">
    @foreach($actions as $action)
        @if($action['type'] === 'primary')
            <a href="{{ $action['route'] }}" 
               class="bg-slate-700/40 hover:bg-slate-600/40 text-slate-200 px-3 py-1.5 rounded text-sm transition-all duration-200 border border-slate-600/20">
                {{ $action['label'] }}
            </a>
        @elseif($action['type'] === 'secondary')
            <a href="{{ $action['route'] }}" 
               class="bg-slate-800/20 hover:bg-slate-700/20 text-slate-300 px-3 py-1.5 rounded text-sm transition-all duration-200 border border-slate-600/10">
                {{ $action['label'] }}
            </a>
        @elseif($action['type'] === 'danger')
            <button onclick="confirmDelete('{{ $action['route'] }}')"
                    class="bg-red-900/20 hover:bg-red-800/20 text-red-400 px-3 py-1.5 rounded text-sm transition-all duration-200 border border-red-700/20">
                {{ $action['label'] }}
            </button>
        @endif
    @endforeach
</div>
