<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('dashboard._stats-cards')

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Distribution des recommandations') }}</h3>
                @include('dashboard._recommendation-distribution')
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Classement des candidats') }}</h3>

                @if ($totalCandidats > 0)
                    @include('candidates._ranking-table', ['candidats' => $candidats, 'showOffre' => true])

                    <div class="mt-4">
                        {{ $candidats->links() }}
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">{{ __('Aucun candidat pour le moment.') }}</p>
                    <a href="{{ route('offres.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Créer une offre') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
