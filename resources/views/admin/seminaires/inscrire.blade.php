<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Contenu converti depuis layouts.admin -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6">
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">🚧</div>
                        <h3 class="text-lg font-semibold text-slate-100 tracking-tight flex items-center gap-3">Page en cours de migration</h3>
                        <p class="text-gray-600 mb-6">Cette page utilise encore l'ancien layout</p>
                        <x-secondary-button href="{{ url()->previous() }}">
                            Retour
                        </x-secondary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
