@props([
    'headers' => [],
    'rows' => [],
    'actions' => true,
    'striped' => true,
    'hoverable' => true
])

<div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-300']) }}>
        @if(!empty($headers))
            <thead class="bg-gray-50">
                <tr>
                    @foreach($headers as $header)
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                    @if($actions)
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    @endif
                </tr>
            </thead>
        @endif
        
        <tbody class="bg-white divide-y divide-gray-200">
            {{ $slot }}
        </tbody>
    </table>
</div>
