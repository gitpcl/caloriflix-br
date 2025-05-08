<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col space-y-4">
        <!-- Header with title and actions -->
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-medium text-gray-900 dark:text-white">Alimentos cadastrados</h1>
            <div class="flex items-center space-x-2">
                <!-- Search input -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar..." class="pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg w-full dark:bg-gray-700 dark:text-white text-sm">
                </div>
                
                <!-- Action buttons -->
                <button 
                    wire:click="openCreateFoodModal"
                    class="flex items-center justify-center h-10 w-10 rounded-lg bg-indigo-100 dark:bg-indigo-800 text-indigo-600 dark:text-indigo-200 hover:bg-indigo-200 dark:hover:bg-indigo-700 transition"
                >
                    <span class="text-xl font-semibold">+</span>
                    <span class="sr-only">Adicionar alimento</span>
                </button>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="flex items-center space-x-2 text-sm py-1 px-2">
            <button class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded hover:bg-green-200 flex items-center">
                <span>Todos</span>
            </button>
            <button class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span>Filtrar</span>
            </button>
            <button class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                </svg>
                <span>Ordenar</span>
            </button>
            <button class="p-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
            <button class="p-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                </svg>
            </button>
        </div>
        
        <!-- Food List -->
        <div class="grid grid-cols-1 gap-4">
            @if (session()->has('message'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800">
                    {{ session('message') }}
                </div>
            @endif
            
            @forelse ($foods as $food)
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-md border border-gray-100 dark:border-gray-700 overflow-hidden cursor-pointer" wire:click="openFoodEditModal({{ $food->id }})">
                    <div class="px-4 py-3 flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <!-- Food icon based on type -->
                            <div class="flex-shrink-0">
                                @if (strpos(strtolower($food->name), 'protein') !== false || strpos(strtolower($food->name), 'barra') !== false)
                                    <div class="bg-red-100 rounded p-1"><span class="text-xl">🍫</span></div>
                                @elseif (strpos(strtolower($food->name), 'shake') !== false || strpos(strtolower($food->name), 'fairlife') !== false)
                                    <div class="bg-red-100 rounded p-1"><span class="text-xl">🥤</span></div>
                                @elseif (strpos(strtolower($food->name), 'wrap') !== false || strpos(strtolower($food->name), 'carb') !== false)
                                    <div class="bg-amber-100 rounded p-1"><span class="text-xl">🌯</span></div>
                                @elseif (strpos(strtolower($food->name), 'queijo') !== false || strpos(strtolower($food->name), 'queso') !== false)
                                    <div class="bg-yellow-100 rounded p-1"><span class="text-xl">🧀</span></div>
                                @else
                                    <div class="bg-green-100 rounded p-1"><span class="text-xl">🍽️</span></div>
                                @endif
                            </div>
                            
                            <!-- Food details -->
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white leading-tight">
                                    {{ floor($food->quantity) }}{{ strtolower($food->unit) == 'ml' || strtolower($food->unit) == 'gramas' ? 'g' : ' ' . $food->unit }} {{ $food->name }}
                                </h3>
                                <div class="mt-0.5">
                                    <div class="text-xs text-blue-600 dark:text-blue-400 leading-tight">
                                        {{ floor($food->protein) }}g prot · {{ floor($food->fat) }}g gord · {{ floor($food->carbohydrate) }}g carb · {{ floor($food->calories) }}kcal
                                    </div>
                                    <a href="#" class="text-xs text-blue-600 dark:text-blue-400 underline">Tabela nutricional</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Add button -->
                        <button 
                            wire:click.stop="openFoodDetailModal({{ $food->id }})" 
                            class="h-8 w-8 flex-shrink-0 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:text-gray-800 hover:border-gray-300 focus:outline-none shadow-sm"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-gray-500 dark:text-gray-400 text-center">
                        Nenhum alimento encontrado. Clique no botão "Alimento" para adicionar um novo.
                    </div>
                </div>
            @endforelse
            
            <div class="mt-4">
                {{ $foods->links() }}
            </div>
        </div>
    </div>
    
    <!-- Create Food Modal -->    
    <div x-data="{ show: @entangle('showCreateFoodModal') }" x-show="show" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div 
                @click.outside="show = false"
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full"
                role="dialog" 
                aria-modal="true" 
                aria-labelledby="modal-headline">
                
                <!-- Modal Header -->    
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Adicionar alimento</h3>
                    <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="px-4 py-4">
                    <form wire:submit.prevent="save">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Nome</label>
                            <input type="text" wire:model="name" id="name" placeholder="Arroz, Feijão, etc." class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="quantity" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Quantidade</label>
                            <input type="number" wire:model="quantity" id="quantity" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="unit" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Unidade</label>
                            <input type="text" wire:model="unit" id="unit" placeholder="gramas, ml, etc." class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('unit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="protein" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Proteína</label>
                            <div class="mt-1 flex rounded-md">
                                <input type="text" wire:model="protein" id="protein" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">
                                    gramas
                                </span>
                            </div>
                            @error('protein') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="fat" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Gordura</label>
                            <div class="mt-1 flex rounded-md">
                                <input type="text" wire:model="fat" id="fat" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">
                                    gramas
                                </span>
                            </div>
                            @error('fat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="carbohydrate" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Carboidrato</label>
                            <div class="mt-1 flex rounded-md">
                                <input type="text" wire:model="carbohydrate" id="carbohydrate" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">
                                    gramas
                                </span>
                            </div>
                            @error('carbohydrate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="fiber" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Fibras</label>
                            <div class="mt-1 flex rounded-md">
                                <input type="text" wire:model="fiber" id="fiber" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">
                                    gramas
                                </span>
                            </div>
                            @error('fiber') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="calories" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Calorias</label>
                            <div class="mt-1 flex rounded-md">
                                <input type="text" wire:model="calories" id="calories" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">
                                    kcal
                                </span>
                            </div>
                            @error('calories') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="barcode" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Código de barras <span class="text-gray-400 text-xs">opcional</span></label>
                            <input type="text" wire:model="barcode" id="barcode" placeholder="123456789012" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('barcode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <button type="submit" class="w-full py-2 px-4 border border-transparent font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Salvar
                        </button>
                    </form>
                </div>                
            </div>
        </div>
    </div>
    
    <!-- Food Detail Modal -->
    @if ($selectedFood)
    <div x-data="{ show: @entangle('showFoodDetailModal') }" x-show="show" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div 
                @click.outside="show = false"
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full"
                role="dialog" 
                aria-modal="true" 
                aria-labelledby="modal-headline">
                
                <!-- Modal Header -->
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white"></h3>
                    <button type="button" wire:click="closeModals" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="px-6 py-4">
                    <!-- Food Title with Nutritional Info -->
                    <div class="text-center mb-6">
                        <h2 class="text-xl font-medium text-gray-900 dark:text-white mb-1">{{ floor($selectedFood->quantity) }}g {{ $selectedFood->name }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ floor($selectedFood->protein) }}g prot · {{ floor($selectedFood->fat) }}g gord · {{ floor($selectedFood->carbohydrate) }}g carb · {{ floor($selectedFood->calories) }}kcal</p>
                    </div>
                    
                    <!-- Quantity Adjustment -->
                    <div class="mb-6">
                        <label class="block text-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alterar quantidade:</label>
                        <div class="flex items-center justify-center">
                            <button 
                                wire:click="updateFoodQuantity({{ $foodQuantity - 5 }})" 
                                class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-l text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600"
                            >
                                –
                            </button>
                            <input 
                                type="number" 
                                wire:model.debounce.500ms="foodQuantity" 
                                min="5" 
                                class="w-16 py-1 text-center border-t border-b border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                            >
                            <button 
                                wire:click="updateFoodQuantity({{ $foodQuantity + 5 }})" 
                                class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-r text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600"
                            >
                                +
                            </button>
                        </div>
                    </div>
                    
                    <!-- Used Recently Option -->
                    <div class="flex items-center justify-center mb-4">
                        <input id="recently-used" type="checkbox" wire:model="recentlyUsed" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="recently-used" class="ml-2 block text-sm text-gray-600 dark:text-gray-400">OK, usados recentemente</label>
                    </div>
                    
                    <!-- No Records Message -->
                    <div class="text-center text-sm text-gray-500 dark:text-gray-400 mb-6">
                        Sem registros
                    </div>
                    
                    <!-- Add to Meal Button -->
                    <div class="mb-8">
                        <div x-data="{ open: false }" class="relative">
                            <button 
                                @click="open = !open" 
                                type="button" 
                                class="w-full flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                <span>Adicionar em {{ $selectedMeal }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            
                            <div 
                                x-show="open" 
                                @click.away="open = false" 
                                class="absolute z-50 mt-1 w-full rounded-md bg-white dark:bg-gray-700 shadow-lg"
                            >
                                <div class="py-1">
                                    @foreach(['Café da manhã', 'Lanche da manhã', 'Almoço', 'Lanche da tarde', 'Jantar', 'Ceia'] as $meal)
                                        <button 
                                            wire:click="addFoodToMeal('{{ $meal }}')" 
                                            @click="open = false"
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600"
                                        >
                                            {{ $meal }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Records Table -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-800 dark:text-gray-200 mb-3">Registros do alimento: 1</h3>
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Refeição</th>
                                    <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                                    <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr>
                                    <td class="px-2 py-2 text-sm text-gray-900 dark:text-gray-300">{{ $selectedMeal }}</td>
                                    <td class="px-2 py-2 text-sm text-gray-900 dark:text-gray-300">{{ $foodQuantity }} g</td>
                                    <td class="px-2 py-2 text-sm text-gray-900 dark:text-gray-300">
                                        <div class="flex items-center">
                                            <span>{{ now()->format('d/m, H\h') }}</span>
                                            <button class="ml-2 text-gray-400 hover:text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Food Edit Modal -->
    @if($selectedFood && $showFoodEditModal)
    <div x-data="{ show: @entangle('showFoodEditModal') }" x-show="show" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div 
                @click.outside="show = false"
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full"
                role="dialog" 
                aria-modal="true" 
                aria-labelledby="modal-headline">
                
                <!-- Modal Header with Close Button -->
                <div class="flex justify-end p-2">
                    <button type="button" wire:click="closeModals" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="px-4 pb-6">
                    <!-- Food Header with Image and Title -->
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-12 h-12 bg-gray-200 rounded mr-3 flex items-center justify-center overflow-hidden">
                            <img src="https://via.placeholder.com/50" alt="{{ $selectedFood->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="text-center">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">{{ $quantity }}g {{ $name }}</h2>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ now()->format('d/m/Y H:i') }} em Almoço</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <form wire:submit.prevent="updateFood">
                        <!-- Form Fields -->
                        <div class="space-y-4">
                            <!-- Name Field -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        wire:model="name" 
                                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    >
                                    <button type="button" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">Adicionado via WhatsApp</div>
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Quantity Field -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantidade</label>
                                <div class="flex">
                                    <input 
                                        type="number" 
                                        wire:model="quantity" 
                                        step="0.1"
                                        class="w-full border border-gray-300 rounded-l px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    >
                                    <span class="inline-flex items-center px-3 py-2 rounded-r border border-l-0 border-gray-300 bg-gray-50 text-gray-500 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">g</span>
                                </div>
                                @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Quick Quantity Options -->
                            <div class="flex mb-1 text-sm">
                                <span class="text-gray-500 mr-2 self-center">Def. usado recentemente:</span>
                                <div class="flex space-x-1">
                                    <button type="button" wire:click="$set('quantity', 40)" class="bg-gray-200 px-2 py-1 rounded text-gray-700 hover:bg-gray-300 text-xs">40g</button>
                                    <button type="button" wire:click="$set('quantity', 78)" class="bg-gray-200 px-2 py-1 rounded text-gray-700 hover:bg-gray-300 text-xs">78g</button>
                                    <button type="button" wire:click="$set('quantity', 39.5)" class="bg-gray-200 px-2 py-1 rounded text-gray-700 hover:bg-gray-300 text-xs">39.5g</button>
                                </div>
                            </div>
                            
                            <!-- Nutritional Values -->
                            <div>
                                <div class="grid grid-cols-3 gap-2">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Proteína</label>
                                        <div class="flex">
                                            <input 
                                                type="number" 
                                                wire:model="protein"
                                                step="0.1"
                                                class="w-full border border-gray-300 rounded-l px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            >
                                            <span class="inline-flex items-center px-2 py-2 rounded-r border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">gramas</span>
                                        </div>
                                        @error('protein') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gordura</label>
                                        <div class="flex">
                                            <input 
                                                type="number" 
                                                wire:model="fat"
                                                step="0.1"
                                                class="w-full border border-gray-300 rounded-l px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            >
                                            <span class="inline-flex items-center px-2 py-2 rounded-r border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">gramas</span>
                                        </div>
                                        @error('fat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Carboidrato</label>
                                        <div class="flex">
                                            <input 
                                                type="number" 
                                                wire:model="carbohydrate"
                                                step="0.1"
                                                class="w-full border border-gray-300 rounded-l px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            >
                                            <span class="inline-flex items-center px-2 py-2 rounded-r border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">gramas</span>
                                        </div>
                                        @error('carbohydrate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fibras</label>
                                        <div class="flex">
                                            <input 
                                                type="number" 
                                                wire:model="fiber"
                                                step="0.1"
                                                class="w-full border border-gray-300 rounded-l px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            >
                                            <span class="inline-flex items-center px-2 py-2 rounded-r border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">gramas</span>
                                        </div>
                                        @error('fiber') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Calorias</label>
                                        <div class="flex">
                                            <input 
                                                type="number" 
                                                wire:model="calories"
                                                step="1"
                                                class="w-full border border-gray-300 rounded-l px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            >
                                            <span class="inline-flex items-center px-2 py-2 rounded-r border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-xs dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">kcal</span>
                                        </div>
                                        @error('calories') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="pt-4 flex justify-center">
                                <button type="button" wire:click="delete({{ $selectedFood->id }})" class="flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none mr-2">
                                    <span>Excluir</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button type="submit" class="flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none">
                                    <span>Salvar</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Records Section -->
                    <div class="mt-8">
                        <h3 class="text-sm font-medium text-gray-800 dark:text-gray-200 mb-3">Registros do alimento: 1</h3>
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Refeição</th>
                                    <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                                    <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr>
                                    <td class="px-2 py-2 text-sm text-gray-900 dark:text-gray-300">Almoço</td>
                                    <td class="px-2 py-2 text-sm text-gray-900 dark:text-gray-300">{{ $quantity }} g</td>
                                    <td class="px-2 py-2 text-sm text-gray-900 dark:text-gray-300">
                                        <div class="flex items-center">
                                            <span>08/04, 12h</span>
                                            <button class="ml-2 text-gray-400 hover:text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
