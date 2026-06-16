<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $candidat->nom }}
            </h2>
            <a href="{{ route('offres.show', $offre) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Nom') }}</dt>
                            <dd class="mt-1 text-sm">{{ $candidat->nom }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Statut') }}</dt>
                            <dd class="mt-1">
<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
    @switch($candidat->statut_job->value)
        @case('en_cours') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @break
        @case('analyse') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
        @case('echec') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @break
        @default bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
    @endswitch">
    @switch($candidat->statut_job->value)
        @case('en_cours') {{ __('Analyse en cours') }} @break
        @case('analyse') {{ __('Analysé') }} @break
        @case('echec') {{ __('Échec') }} @break
        @default {{ __('En attente') }}
    @endswitch
                                </span>
                            </dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('CV') }}</dt>
                            <dd class="mt-1 text-sm whitespace-pre-wrap">{{ $candidat->cv_texte }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            @if ($candidat->analyse)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Analyse IA') }}</h3>

                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Score de matching') }}</dt>
                                <dd class="mt-1 text-sm">{{ $candidat->analyse->matching_score }}/100</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Niveau d\'études') }}</dt>
                                <dd class="mt-1 text-sm">{{ $candidat->analyse->niveau_etudes }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Années d\'expérience') }}</dt>
                                <dd class="mt-1 text-sm">{{ $candidat->analyse->annees_experience }} an(s)</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Recommandation') }}</dt>
                                <dd class="mt-1 text-sm">{{ $candidat->analyse->recommandation?->value ?? '—' }}</dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Compétences extraites') }}</dt>
                                <dd class="mt-1 flex flex-wrap gap-2">
                                    @foreach ($candidat->analyse->competences_extraites as $competence)
                                        <span class="px-2 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 text-xs font-medium rounded-full">{{ $competence }}</span>
                                    @endforeach
                                </dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Points forts') }}</dt>
                                <dd class="mt-1 text-sm">
                                    <ul class="list-disc list-inside">
                                        @foreach ($candidat->analyse->points_forts as $point)
                                            <li>{{ $point }}</li>
                                        @endforeach
                                    </ul>
                                </dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Lacunes') }}</dt>
                                <dd class="mt-1 text-sm">
                                    <ul class="list-disc list-inside">
                                        @foreach ($candidat->analyse->lacunes as $lacune)
                                            <li>{{ $lacune }}</li>
                                        @endforeach
                                    </ul>
                                </dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Justification') }}</dt>
                                <dd class="mt-1 text-sm whitespace-pre-wrap">{{ $candidat->analyse->justification }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Analyse IA') }}</h3>
                        <div class="flex items-center gap-3 text-gray-500 dark:text-gray-400">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p>{{ __("L'analyse est en cours. Rechargez la page pour voir les résultats.") }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
