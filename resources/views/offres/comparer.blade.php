<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Comparaison') }} — {{ $offre->titre }}
            </h2>
            <a href="{{ route('offres.show', $offre) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-2 gap-6">
                @php
                    $isBest1 = $candidat1->candidat_id === $best->candidat_id;
                    $scoreColor = function ($score) {
                        if ($score === null || $score < 0) return 'bg-gray-300 dark:bg-gray-500';
                        if ($score >= 70) return 'bg-green-500';
                        if ($score >= 40) return 'bg-yellow-500';
                        return 'bg-red-500';
                    };
                    $recBadge = function ($rec) {
                        return match ($rec?->value) {
                            'convoquer' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                            'attente' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                            'rejeter' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                        };
                    };
                    $recLabel = function ($rec) {
                        return match ($rec?->value) {
                            'convoquer' => __('À convoquer'),
                            'attente' => __('En attente'),
                            'rejeter' => __('À rejeter'),
                            default => '—',
                        };
                    };
                @endphp

                @foreach ([$candidat1, $candidat2] as $candidat)
                    @php
                        $isBest = $candidat->candidat_id === $best->candidat_id;
                    @endphp
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg {{ $isBest ? 'ring-2 ring-green-500' : '' }}">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            @if ($isBest)
                                <span class="inline-block px-3 py-1 mb-3 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs font-semibold rounded-full">
                                    {{ __('Meilleur profil') }}
                                </span>
                            @endif

                            <h3 class="text-xl font-bold mb-4">{{ $candidat->candidat->nom }}</h3>

                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Score de matching') }}</dt>
                                    <dd class="mt-1">
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 bg-gray-200 dark:bg-gray-600 rounded-full h-3">
                                                <div class="h-3 rounded-full transition-all {{ $scoreColor($candidat->matching_score) }}" style="width: {{ $candidat->matching_score }}%"></div>
                                            </div>
                                            <span class="text-lg font-semibold">{{ $candidat->matching_score }}%</span>
                                        </div>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Recommandation') }}</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $recBadge($candidat->recommandation) }}">
                                            {{ $recLabel($candidat->recommandation) }}
                                        </span>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Années d\'expérience') }}</dt>
                                    <dd class="mt-1 text-sm">{{ $candidat->annees_experience }} an(s)</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Niveau d\'études') }}</dt>
                                    <dd class="mt-1 text-sm">{{ $candidat->niveau_etudes }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Compétences extraites') }}</dt>
                                    <dd class="mt-1 flex flex-wrap gap-2">
                                        @foreach ($candidat->competences_extraites as $competence)
                                            <span class="px-2 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 text-xs font-medium rounded-full">{{ $competence }}</span>
                                        @endforeach
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Points forts') }}</dt>
                                    <dd class="mt-1 text-sm">
                                        <ul class="list-disc list-inside">
                                            @foreach ($candidat->points_forts as $point)
                                                <li>{{ $point }}</li>
                                            @endforeach
                                        </ul>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Lacunes') }}</dt>
                                    <dd class="mt-1 text-sm">
                                        <ul class="list-disc list-inside">
                                            @foreach ($candidat->lacunes as $lacune)
                                                <li>{{ $lacune }}</li>
                                            @endforeach
                                        </ul>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Justification') }}</dt>
                                    <dd class="mt-1 text-sm whitespace-pre-wrap">{{ $candidat->justification }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center text-gray-700 dark:text-gray-300">
                    <span class="text-lg font-semibold">{{ __('Écart de score') }} : </span>
                    <span class="text-2xl font-bold {{ $scoreGap >= 20 ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                        {{ $scoreGap }}%
                    </span>
                </div>
            </div>

            <div class="flex justify-center">
                <form method="GET" action="{{ route('offres.comparer', $offre) }}">
                    <input type="hidden" name="candidats[]" value="{{ $candidat1->candidat_id }}">
                    <input type="hidden" name="candidats[]" value="{{ $candidat2->candidat_id }}">
                    <x-primary-button type="submit">
                        {{ __('↻ Recharger la comparaison') }}
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
