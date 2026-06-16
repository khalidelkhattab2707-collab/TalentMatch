<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $conversation->title }}
            </h2>
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="space-y-4 mb-6 max-h-[60vh] overflow-y-auto" id="messages">
                        @forelse ($conversation->messages as $message)
                            @if (in_array($message->role, ['user', 'assistant']))
                                <div class="flex {{ $message->role === 'user' ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[80%] rounded-lg px-4 py-3 {{ $message->role === 'user' ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-900 dark:text-indigo-100' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100' }}">
                                        <p class="text-sm whitespace-pre-wrap">{{ $message->content }}</p>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <p class="text-center text-gray-500 dark:text-gray-400">{{ __('Aucun message.') }}</p>
                        @endforelse
                    </div>

                    <form method="POST" action="{{ route('conversations.message', $conversation) }}" class="flex gap-3">
                        @csrf
                        <input type="text" name="contenu" placeholder="{{ __('Posez votre question...') }}" required
                               class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <x-primary-button>{{ __('Envoyer') }}</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
