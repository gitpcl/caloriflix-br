<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detalhes do Alimento') }}
            </h2>
            <div>
                <x-secondary-button onclick="history.back()">
                    {{ __('Voltar') }}
                </x-secondary-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <livewire:food-details :foodId="$foodId" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
