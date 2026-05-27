<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Редактировать заметку
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <form method="POST" action="{{ route('notes.update', $note) }}">
                    @csrf
                    <div>
                        <label for="title" class="block font-medium text-sm text-gray-700">
                            Заголовок
                        </label>
                        <input id="title"
                               name="title"
                               type="text"
                               value="{{ old('title', $note->title) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                               required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label for="content" class="block font-medium text-sm text-gray-700">
                            Текст
                        </label>
                        <textarea id="content"
                                  name="content"
                                  rows="8"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('content', $note->content) }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4" x-data="{ color: '{{ old('color', $note->color) }}' }">
                        <label class="block font-medium text-sm text-gray-700">
                            Цвет
                        </label>

                        <input type="hidden" name="color" x-model="color">

                        <div class="mt-2 flex gap-3">
                            @foreach (['#6366f1', '#ffffff', '#fef3c7', '#dcfce7', '#dbeafe', '#fce7f3', '#ede9fe'] as $color)
                                <button type="button"
                                        @click="color = '{{ $color }}'"
                                        class="w-9 h-9 rounded-full border-2"
                                        :class="color === '{{ $color }}' ? 'border-gray-900' : 'border-gray-300'"
                                        style="background-color: {{ $color }}">
                                </button>
                            @endforeach
                        </div>

                        @error('color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('notes.index') }}"
                           class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">
                            Отмена
                        </a>

                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Обновить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>