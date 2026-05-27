<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Заметки
            </h2>

            <a href="{{ route('notes.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Новая заметка
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <form method="GET" action="{{ route('notes.index') }}" class="mb-6">
                <div class="flex gap-2">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Поиск по названию или тексту..."
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                    <button type="submit"
                            class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-900">
                        Найти
                    </button>

                    @if ($search)
                        <a href="{{ route('notes.index') }}"
                           class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                            Сброс
                        </a>
                    @endif
                </div>
            </form>

            @if ($notes->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($notes as $note)
                        <div class="rounded-xl shadow-sm border border-gray-200 p-5"
                             style="background-color: {{ $note->color }}"
                             x-data="{
                                pinned: @js($note->is_pinned),
                                async togglePin() {
                                    const response = await fetch('{{ route('notes.toggle-pin', $note) }}', {
                                        method: 'PATCH',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json',
                                            'Content-Type': 'application/json'
                                        }
                                    });

                                    if (response.ok) {
                                        const data = await response.json();
                                        this.pinned = data.is_pinned;
                                    }
                                }
                             }">
                            <div class="flex justify-between gap-4">
                                <h3 class="text-lg font-bold text-gray-900 break-words">
                                    {{ $note->title }}
                                </h3>

                                <button type="button"
                                        @click="togglePin"
                                        class="text-xl"
                                        :title="pinned ? 'Открепить' : 'Закрепить'">
                                    <span x-show="pinned">📌</span>
                                    <span x-show="!pinned">📍</span>
                                </button>
                            </div>

                            <p class="mt-3 text-gray-800 whitespace-pre-line break-words">
                                {{ $note->content }}
                            </p>

                            <div class="mt-4 text-xs text-gray-600">
                                Обновлено: {{ $note->updated_at->format('d.m.Y H:i') }}
                            </div>

                            <div class="mt-5 flex items-center justify-between">
                                <a href="{{ route('notes.edit', $note) }}"
                                   class="text-indigo-700 hover:underline">
                                    Редактировать
                                </a>

                                <form method="POST"
                                      action="{{ route('notes.destroy', $note) }}"
                                      onsubmit="return confirm('Удалить заметку?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="text-red-700 hover:underline">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $notes->links() }}
                </div>
            @else
                <div class="bg-white p-8 rounded-xl shadow-sm text-center text-gray-600">
                    Заметок пока нет.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>