<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nouvelle offre') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('offres.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="titre" :value="__('Titre')" />
                            <x-text-input id="titre" name="titre" type="text" class="mt-1 block w-full" :value="old('titre')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('titre')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="6" required>{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="competences_requises" :value="__('Compétences requises')" />
                            <div id="competences-wrapper" class="mt-1 space-y-2">
                                @foreach (old('competences_requises', ['']) as $index => $competence)
                                    <div class="flex gap-2">
                                        <input type="text" name="competences_requises[]" value="{{ $competence }}" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="PHP" />
                                        <button type="button" class="remove-competence px-3 py-2 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">×</button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-competence" class="mt-2 text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">{{ __('+ Ajouter une compétence') }}</button>
                            <x-input-error class="mt-2" :messages="$errors->get('competences_requises')" />
                        </div>

                        <div>
                            <x-input-label for="experience_minimum" :value="__('Expérience minimum (années)')" />
                            <x-text-input id="experience_minimum" name="experience_minimum" type="number" class="mt-1 block w-full" :value="old('experience_minimum', 0)" min="0" max="30" required />
                            <x-input-error class="mt-2" :messages="$errors->get('experience_minimum')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Créer') }}</x-primary-button>
                            <a href="{{ route('offres.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">{{ __('Annuler') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('add-competence')?.addEventListener('click', function () {
            const wrapper = document.getElementById('competences-wrapper');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = '<input type="text" name="competences_requises[]" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="PHP" />' +
                '<button type="button" class="remove-competence px-3 py-2 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">×</button>';
            wrapper.appendChild(div);
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-competence')) {
                const wrapper = document.getElementById('competences-wrapper');
                if (wrapper.children.length > 1) {
                    e.target.closest('.flex').remove();
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
