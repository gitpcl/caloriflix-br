<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Adicionar Alimento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('foods.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div>
                            <x-input-label for="name" :value="__('Nome do Alimento')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        
                        <div>
                            <x-input-label for="description" :value="__('Descrição (opcional)')" />
                            <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="protein" :value="__('Proteínas (g)')" />
                                <x-text-input id="protein" name="protein" type="number" step="0.1" min="0" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('protein')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="carbs" :value="__('Carboidratos (g)')" />
                                <x-text-input id="carbs" name="carbs" type="number" step="0.1" min="0" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('carbs')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="fat" :value="__('Gorduras (g)')" />
                                <x-text-input id="fat" name="fat" type="number" step="0.1" min="0" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('fat')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="calories" :value="__('Calorias (kcal)')" />
                                <x-text-input id="calories" name="calories" type="number" step="1" min="0" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('calories')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="serving_size" :value="__('Tamanho da Porção')" />
                                <x-text-input id="serving_size" name="serving_size" type="number" step="1" min="0" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('serving_size')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="serving_unit" :value="__('Unidade (g, ml, etc)')" />
                                <x-text-input id="serving_unit" name="serving_unit" type="text" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('serving_unit')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <input id="is_favorite" name="is_favorite" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                            <label for="is_favorite" class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Marcar como favorito') }}</label>
                        </div>
                        
                        <div class="flex items-center justify-end mt-4">
                            <x-secondary-button onclick="history.back()" class="mr-3">
                                {{ __('Cancelar') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Salvar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
