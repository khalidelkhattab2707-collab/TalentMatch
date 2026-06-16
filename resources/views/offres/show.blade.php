<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $offre->titre }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('offres.edit', $offre) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Modifier') }}
                </a>
                <a href="{{ route('offres.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Retour') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Statut') }}</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $offre->statut === \App\Enums\StatutOffreEnum::Active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' }}">
                                    {{ $offre->statut->value === 'active' ? 'Active' : 'Archivée' }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Expérience minimum') }}</dt>
                            <dd class="mt-1 text-sm">{{ $offre->experience_minimum }} an(s)</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Description') }}</dt>
                            <dd class="mt-1 text-sm whitespace-pre-wrap">{{ $offre->description }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Compétences requises') }}</dt>
                            <dd class="mt-1 flex flex-wrap gap-2">
                                @foreach ($offre->competences_requises as $competence)
                                    <span class="px-2 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 text-xs font-medium rounded-full">{{ $competence }}</span>
                                @endforeach
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">{{ __('Candidats') }} ({{ $offre->candidats->count() }})</h3>
                        <div class="flex gap-2">
                            <a href="{{ route('offres.candidats.create', $offre) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Ajouter un candidat') }}
                            </a>
                        </div>
                    </div>

                    @if ($candidats->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">{{ __('Aucun candidat pour cette offre.') }}</p>
                    @else
                        <form method="GET" action="{{ route('offres.comparer', $offre) }}" x-data="{ selected: [] }">
                            @include('candidates._ranking-table', [
                                'candidats' => $candidats,
                                'showOffre' => false,
                                'showCheckboxes' => true,
                            ])

                            <div class="mt-4 flex items-center gap-4">
                                <template x-for="id in selected" :key="id">
                                    <input type="hidden" name="candidats[]" :value="id">
                                </template>
                                <x-primary-button
                                    x-bind:disabled="selected.length !== 2"
                                    x-text="selected.length === 2 ? '{{ __('Comparer') }} (2)' : (selected.length === 0 ? '{{ __('Sélectionner 2 candidats') }}' : '{{ __('Sélectionner 2 candidats') }} (' + selected.length + '/2)')"
                                >
                                </x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
