@props(['metrics'])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    @foreach($metrics as $metric)
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">{{ $metric['label'] }}</p>
                    <p class="text-3xl font-bold text-white">{{ $metric['value'] }}</p>
                    @if(isset($metric['subtitle']))
                        <p class="text-gray-500 text-xs mt-1">{{ $metric['subtitle'] }}</p>
                    @endif
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" 
                     style="background-color: {{ $metric['color'] ?? '#6366f1' }};">
                    <span class="text-2xl">{{ $metric['icon'] }}</span>
                </div>
            </div>
        </div>
    @endforeach
</div>
