<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('À convoquer') }}</dt>
        <dd class="mt-1 text-3xl font-semibold text-green-600 dark:text-green-400">{{ $distribution['convoquer'] ?? 0 }}</dd>
    </div>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('En attente') }}</dt>
        <dd class="mt-1 text-3xl font-semibold text-yellow-600 dark:text-yellow-400">{{ $distribution['attente'] ?? 0 }}</dd>
    </div>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('À rejeter') }}</dt>
        <dd class="mt-1 text-3xl font-semibold text-red-600 dark:text-red-400">{{ $distribution['rejeter'] ?? 0 }}</dd>
    </div>
</div>
