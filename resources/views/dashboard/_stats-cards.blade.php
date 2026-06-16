<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total des offres') }}</dt>
        <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalOffres }}</dd>
    </div>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total des candidats') }}</dt>
        <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalCandidats }}</dd>
    </div>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Candidats analysés') }}</dt>
        <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $analysedCount }}</dd>
    </div>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Score moyen') }}</dt>
        <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $avgScore ? round($avgScore) : '—' }}</dd>
    </div>
</div>
