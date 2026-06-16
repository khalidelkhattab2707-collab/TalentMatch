<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nouveau candidat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('offres.candidats.store', $offre) }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="nom" :value="__('Nom du candidat')" />
                            <x-text-input id="nom" name="nom" type="text" class="mt-1 block w-full" :value="old('nom')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('nom')" />
                        </div>

                        <div>
                            <x-input-label for="cv_texte" :value="__('Texte du CV')" />
                            <textarea id="cv_texte" name="cv_texte" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="10" required>{{ old('cv_texte') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('cv_texte')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Ajouter') }}</x-primary-button>
                            <a href="{{ route('offres.show', $offre) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">{{ __('Annuler') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
