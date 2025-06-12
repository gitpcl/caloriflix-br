<div
    x-data="{
        showNotification: false,
        notificationMessage: '',
        notificationType: 'success',
        init() {
            this.setupListeners();
        },
        setupListeners() {
            window.addEventListener('notify', (event) => {
                this.notificationMessage = event.detail.message;
                this.notificationType = event.detail.type || 'success';
                this.showNotification = true;
                
                // Auto-hide notification after 5 seconds
                setTimeout(() => {
                    this.showNotification = false;
                }, 5000);
            });
        }
    }"
    x-cloak
    class="recipes-component"
>
    <!-- Notification -->
    <div 
        x-show="showNotification" 
        x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end"
        style="z-index: 9999;"
    >
        <div 
            :class="{ 
                'bg-caloriflix-50 border-caloriflix-500': notificationType === 'success',
                'bg-red-50 border-red-500': notificationType === 'error',
                'bg-blue-50 border-blue-500': notificationType === 'info'
            }"
            class="max-w-sm w-full border-l-4 shadow-lg rounded-lg pointer-events-auto"
        >
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <template x-if="notificationType === 'success'">
                            <svg class="h-6 w-6 text-caloriflix-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </template>
                        <template x-if="notificationType === 'error'">
                            <svg class="h-6 w-6 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </template>
                        <template x-if="notificationType === 'info'">
                            <svg class="h-6 w-6 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </template>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p x-text="notificationMessage" class="text-sm font-medium text-neutral-900"></p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button 
                            @click="showNotification = false" 
                            class="inline-flex text-neutral-400 hover:text-neutral-500 focus:outline-none focus:text-neutral-500 transition ease-in-out duration-150"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Session Message -->
    @if (session()->has('message'))
        <div class="bg-caloriflix-50 border-l-4 border-caloriflix-500 p-4 mb-6 rounded shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-caloriflix-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-caloriflix-700">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-6">
            <div class="bg-white dark:bg-neutral-800 overflow-hidden sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-200">Receitas</h2>
                    <div>
                        <div class="inline-flex rounded-md">
                            <button 
                                type="button" 
                                wire:click="openCreateRecipeModal"
                                class="inline-flex items-center p-2 bg-neutral-100 border border-transparent rounded-md font-semibold text-xs text-neutral-900 uppercase tracking-widest hover:bg-neutral-200 focus:bg-neutral-200 active:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-neutral-200 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14m8-8H4" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Search Bar -->
                <div class="mb-6">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search" 
                            class="pl-10 px-2 pr-2 py-2 block w-full rounded-md border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white shadow-sm focus:border-caloriflix-300 focus:ring-caloriflix-300"
                            placeholder="Pesquisar receitas..."
                        >
                    </div>
                </div>
                
                @if($this->recipes->isEmpty())
                    <div class="text-center py-12">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-neutral-100 dark:bg-neutral-700 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-neutral-900 dark:text-neutral-100">Sem receitas cadastradas</h3>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                            Comece criando sua primeira receita agora.
                        </p>
                        <div class="mt-6">
                            <button 
                                type="button" 
                                wire:click="openCreateRecipeModal"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-caloriflix-300 hover:bg-caloriflix-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-caloriflix-500"
                            >
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Criar primeira receita
                            </button>
                        </div>
                    </div>
                @else
                    <!-- Recipes List -->
                    <div class="space-y-4">
                        @foreach($this->recipes as $recipe)
                            <div class="bg-white dark:bg-neutral-700 rounded-lg shadow-sm overflow-hidden">
                                <div class="bg-neutral-50 dark:bg-neutral-800 px-4 py-3 border-b border-neutral-200 dark:border-neutral-600 flex justify-between items-center">
                                    <div>
                                        <h3 class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                            {{ $recipe->name }}
                                            @if($recipe->servings)
                                                <span class="ml-2 text-xs text-neutral-500 dark:text-neutral-400">
                                                    {{ $recipe->servings }} porções
                                                </span>
                                            @endif
                                        </h3>
                                        @if($recipe->total_time)
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                                Tempo total: {{ $recipe->total_time }} min
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex space-x-2">
                                        <button 
                                            wire:click="openRecipeDetailModal({{ $recipe->id }})"
                                            class="text-neutral-400 hover:text-blue-500 focus:outline-none"
                                            title="Adicionar à refeição"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                        <button 
                                            wire:click="openRecipeViewModal({{ $recipe->id }})"
                                            class="text-neutral-400 hover:text-caloriflix-500 focus:outline-none"
                                            title="Editar receita"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        <button 
                                            wire:click="delete({{ $recipe->id }})"
                                            onclick="confirm('Tem certeza que deseja excluir esta receita?') || event.stopImmediatePropagation()"
                                            class="text-neutral-400 hover:text-red-500 focus:outline-none"
                                            title="Excluir receita"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="p-4">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <!-- Nutrition Information -->
                                        @if($recipe->protein || $recipe->carbs || $recipe->fat || $recipe->calories)
                                            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-lg p-3 text-center">
                                                <h4 class="text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Proteína</h4>
                                                <div class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">{{ $recipe->protein ?? 0 }}g</div>
                                            </div>
                                            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-lg p-3 text-center">
                                                <h4 class="text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Carboidratos</h4>
                                                <div class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">{{ $recipe->carbs ?? 0 }}g</div>
                                            </div>
                                            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-lg p-3 text-center">
                                                <h4 class="text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Gordura</h4>
                                                <div class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">{{ $recipe->fat ?? 0 }}g</div>
                                            </div>
                                            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-lg p-3 text-center">
                                                <h4 class="text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">Calorias</h4>
                                                <div class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">{{ $recipe->calories ?? 0 }}</div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($recipe->ingredients)
                                        <div class="mt-4">
                                            <h4 class="text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Ingredientes</h4>
                                            <p class="text-sm text-neutral-600 dark:text-neutral-400 line-clamp-2">{{ Str::limit($recipe->ingredients, 150) }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $this->recipes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recipe Detail Modal -->
    <div
        x-data="{}"
        x-show="$wire.showRecipeDetailModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
    >
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- Backdrop -->
            <div 
                x-show="$wire.showRecipeDetailModal"
                class="fixed inset-0 bg-neutral-500 opacity-60"
                @click="$wire.closeModals()"
            ></div>
            
            <!-- Modal content -->
            <div 
                x-show="$wire.showRecipeDetailModal"
                class="bg-white rounded-lg max-w-xl w-full z-10 relative"
            >
                <!-- Modal content -->
                @if($selectedRecipe)
                <div>
                    <!-- Header with close button -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-200">
                        <div class="flex items-center gap-2">
                            <div class="text-amber-700">
                                <span class="text-xl">🦃</span>
                            </div>
                            <h3 class="text-lg font-medium text-neutral-900">{{ $selectedRecipe->name }}</h3>
                        </div>
                        <button 
                            type="button" 
                            class="text-neutral-400 hover:text-neutral-500"
                            @click="$wire.closeModals()"
                        >
                            <span class="sr-only">Fechar</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Nutritional info bar -->
                    <div class="flex px-6 py-2 bg-neutral-50 border-b border-neutral-200">
                        <div class="flex items-center gap-3 text-xs text-neutral-500">
                            <div>{{ $quantity }}g prot.</div>
                            <div>4g carb.</div>
                            <div>12g gord.</div>
                            <div class="font-medium">{{ $quantity }}kcal</div>
                        </div>
                    </div>
                    
                    <!-- Quantity selector -->
                    <div class="px-6 py-4">
                        <div class="mb-4">
                            <p class="text-sm text-neutral-700 mb-2">Alterar quantidade:</p>
                            <div class="flex items-center">
                                <button 
                                    type="button" 
                                    class="px-2 py-1 border border-neutral-300 rounded-l-md bg-white hover:bg-neutral-50"
                                    wire:click="decrementQuantity"
                                >
                                    <svg class="h-4 w-4 text-neutral-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <input 
                                    type="text" 
                                    wire:model="quantity" 
                                    class="w-16 border-y border-neutral-300 py-1 text-center text-sm p-2"
                                >
                                <button 
                                    type="button" 
                                    class="px-2 py-1 border border-neutral-300 rounded-r-md bg-white hover:bg-neutral-50"
                                    wire:click="incrementQuantity"
                                >
                                    <svg class="h-4 w-4 text-neutral-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <div class="flex items-center mt-3">
                                <input type="radio" id="remember" class="h-4 w-4 text-caloriflix-600 focus:ring-caloriflix-500 border-neutral-300 rounded">
                                <label for="remember" class="ml-2 block text-xs text-neutral-500">
                                    0mL usados recentemente
                                </label>
                            </div>
                            <div class="mt-1 text-xs text-neutral-500">
                                Sem registros
                            </div>
                        </div>
                        
                        <!-- Previous registrations -->
                        <div class="mt-5">
                            <h4 class="text-xs text-neutral-600 mb-2">Registros do alimento: {{ $selectedRecipe->name }}</h4>
                            <table class="min-w-full text-xs">
                                <thead>
                                    <tr class="border-b border-neutral-200">
                                        <th class="text-left py-2 text-neutral-500 font-medium">Refeição</th>
                                        <th class="text-left py-2 text-neutral-500 font-medium">Quantidade</th>
                                        <th class="text-left py-2 text-neutral-500 font-medium">Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-neutral-100">
                                        <td class="py-2 text-neutral-700">Almoço</td>
                                        <td class="py-2 text-neutral-700">{{ $quantity }}ml</td>
                                        <td class="py-2 text-neutral-500">{{ now()->format('d/m/y, H\h') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Action button -->
                    <div class="px-6 py-4 border-t border-neutral-200">
                        <button 
                            type="button"
                            wire:click="addToMeal"
                            class="w-full flex items-center justify-center px-4 py-2.5 bg-caloriflix-600 text-white rounded-md hover:bg-caloriflix-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-caloriflix-500 shadow-sm"
                        >
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span>Adicionar em</span>
                            <span class="font-medium ml-1">Jantar</span>
                            <svg class="ml-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recipe View/Edit Modal -->
    <div
        x-data="{}"
        x-show="$wire.showRecipeViewModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
    >
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- Backdrop -->
            <div 
                x-show="$wire.showRecipeViewModal"
                class="fixed inset-0 bg-neutral-500 opacity-60"
                @click="$wire.closeModals()"
            ></div>
            
            <!-- Modal content -->
            <div 
                x-show="$wire.showRecipeViewModal"
                class="bg-white rounded-lg max-w-md w-full z-10 relative"
            >
                @if($selectedRecipe)
                <div>
                    <!-- Header with close button -->
                    <div class="flex items-center justify-between px-5 py-4">
                        <div class="flex items-center justify-center w-full">
                            <h3 class="text-base font-medium text-neutral-900 text-center">
                                <span class="text-amber-600 mr-2">🍳</span>
                                {{ $form['quantity'] }}g {{ $selectedRecipe->name }}
                            </h3>
                        </div>
                        <button 
                            type="button" 
                            class="text-neutral-400 hover:text-neutral-500 absolute right-5"
                            @click="$wire.closeModals()"
                        >
                            <span class="sr-only">Fechar</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="px-5 py-1 text-xs text-neutral-500">
                        <div class="flex items-center justify-center">
                            <span>{{ now()->format('d/m/y') }} 11h45 em Jantar ·</span>
                        </div>
                    </div>
                    
                    <!-- Form Fields -->
                    <div class="px-5 py-4">
                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm text-neutral-700">Nome</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input 
                                    type="text" 
                                    id="name" 
                                    wire:model="form.name" 
                                    class="block w-full p-2 pr-10 border-neutral-300 focus:ring-caloriflix-500 focus:border-caloriflix-500 rounded-md sm:text-sm"
                                >
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-amber-600">🍲</span>
                                </div>
                            </div>
                            @error('form.name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-2 text-xs text-neutral-500">Adicionado manualmente</div>
                        
                        <!-- Quantidade -->
                        <div class="mb-5">
                            <label for="quantity" class="block text-sm text-neutral-700">Quantidade</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input 
                                    type="number" 
                                    id="quantity" 
                                    wire:model="form.quantity" 
                                    class="block w-full p-2 rounded-l-md border-neutral-300 focus:ring-caloriflix-500 focus:border-caloriflix-500 sm:text-sm"
                                >
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-neutral-300 bg-neutral-50 text-neutral-500 sm:text-sm">
                                    g
                                </span>
                            </div>
                            @error('form.quantity') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Recently used options -->
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="radio" id="recent-1" name="recent" class="h-4 w-4 text-caloriflix-600 focus:ring-caloriflix-500 border-neutral-300 rounded">
                                <label for="recent-1" class="ml-2 block text-xs text-neutral-500">
                                    Qtd. usados recentemente
                                </label>
                            </div>
                            <div class="mt-2 flex space-x-2">
                                <button class="text-xs bg-neutral-100 px-2 py-1 rounded-md text-neutral-700">50g</button>
                                <button class="text-xs bg-neutral-100 px-2 py-1 rounded-md text-neutral-700">200g</button>
                                <button class="text-xs bg-neutral-100 px-2 py-1 rounded-md text-neutral-700">100g</button>
                            </div>
                        </div>
                        
                        <!-- Nutritional Information -->
                        <div class="space-y-3 mt-4">
                            <!-- Protein -->
                            <div class="flex items-center">
                                <label for="protein" class="block text-sm w-24 text-neutral-700">Proteína</label>
                                <div class="flex-1 flex rounded-md shadow-sm">
                                    <input 
                                        type="number" 
                                        id="protein" 
                                        wire:model="form.protein" 
                                        class="block w-full p-2 rounded-l-md border-neutral-300 focus:ring-caloriflix-500 focus:border-caloriflix-500 sm:text-sm"
                                    >
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-neutral-300 bg-neutral-50 text-neutral-500 text-xs">
                                        gramas
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Fat -->
                            <div class="flex items-center">
                                <label for="fat" class="block text-sm w-24 text-neutral-700">Gordura</label>
                                <div class="flex-1 flex rounded-md shadow-sm">
                                    <input 
                                        type="number" 
                                        id="fat" 
                                        wire:model="form.fat" 
                                        step="0.1"
                                        class="block w-full p-2 rounded-l-md border-neutral-300 focus:ring-caloriflix-500 focus:border-caloriflix-500 sm:text-sm"
                                    >
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-neutral-300 bg-neutral-50 text-neutral-500 text-xs">
                                        gramas
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Carbs -->
                            <div class="flex items-center">
                                <label for="carbs" class="block text-sm w-24 text-neutral-700">Carboidrato</label>
                                <div class="flex-1 flex rounded-md shadow-sm">
                                    <input 
                                        type="number" 
                                        id="carbs" 
                                        wire:model="form.carbs" 
                                        class="block w-full p-2 rounded-l-md border-neutral-300 focus:ring-caloriflix-500 focus:border-caloriflix-500 sm:text-sm"
                                    >
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-neutral-300 bg-neutral-50 text-neutral-500 text-xs">
                                        gramas
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Fiber -->
                            <div class="flex items-center">
                                <label for="fiber" class="block text-sm w-24 text-neutral-700">Fibras</label>
                                <div class="flex-1 flex rounded-md shadow-sm">
                                    <input 
                                        type="number" 
                                        id="fiber" 
                                        wire:model="form.fiber" 
                                        class="block w-full p-2 rounded-l-md border-neutral-300 focus:ring-caloriflix-500 focus:border-caloriflix-500 sm:text-sm"
                                    >
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-neutral-300 bg-neutral-50 text-neutral-500 text-xs">
                                        gramas
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Calories -->
                            <div class="flex items-center">
                                <label for="calories" class="block text-sm w-24 text-neutral-700">Calorias</label>
                                <div class="flex-1 flex rounded-md shadow-sm">
                                    <input 
                                        type="number" 
                                        id="calories" 
                                        wire:model="form.calories" 
                                        class="block w-full p-2 rounded-l-md border-neutral-300 focus:ring-caloriflix-500 focus:border-caloriflix-500 sm:text-sm"
                                    >
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-neutral-300 bg-neutral-50 text-neutral-500 text-xs">
                                        kcal
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Ingredients -->
                        <div class="mt-5">
                            <label for="ingredients" class="block text-sm text-neutral-700">Ingredientes:</label>
                            <div class="mt-1">
                                <textarea
                                    id="ingredients"
                                    wire:model="form.ingredients"
                                    rows="4"
                                    class="block w-full p-2 rounded-md border-neutral-300 focus:ring-caloriflix-500 focus:border-caloriflix-500 sm:text-sm"
                                    placeholder="Quo maxime cum irure"
                                ></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stats & Action buttons -->
                    <div>
                        <div class="px-5 py-2">
                            <div class="flex justify-end">
                                <button 
                                    type="button"
                                    wire:click="deleteRecipe({{ $selectedRecipe->id }})"
                                    class="px-3 py-1.5 border border-red-300 text-red-700 bg-white rounded-md hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 flex items-center text-sm"
                                >
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span class="ml-1">Excluir</span>
                                </button>
                            </div>
                        </div>
                        
                        <div class="px-5 py-4 border-t border-neutral-200">
                            <div class="text-xs mb-3">Registros do alimento: {{ $selectedRecipe->name }}</div>
                            <div class="flex justify-between text-sm text-neutral-500 border-b border-neutral-200 py-2">
                                <div>Refeição</div>
                                <div>Quantidade</div>
                                <div>Data</div>
                            </div>
                            <div class="text-center text-sm py-3 text-neutral-500">Sem registros</div>
                        </div>
                        
                        <div class="px-5 py-4 bg-neutral-50 border-t border-neutral-200 flex justify-end">
                            <button 
                                type="button"
                                wire:click="updateRecipe"
                                class="px-4 py-2 bg-caloriflix-600 text-white rounded-md hover:bg-caloriflix-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-caloriflix-500 shadow-sm"
                            >
                                Salvar Alterações
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Recipe Modal -->
    <div
        x-data="{}"
        x-show="$wire.showCreateRecipeModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
    >
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- Backdrop -->
            <div 
                x-show="$wire.showCreateRecipeModal"
                class="fixed inset-0 bg-neutral-500 opacity-60"
                @click="$wire.closeModals()"
            ></div>
            
            <!-- Modal content -->
            <div 
                x-show="$wire.showCreateRecipeModal"
                class="bg-white rounded-lg max-w-2xl w-full z-10 relative"
            >
                <!-- Header with close button -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-medium text-neutral-900">Cadastrar receita</h3>
                    <button 
                        type="button" 
                        class="text-neutral-400 hover:text-neutral-500"
                        @click="$wire.closeModals()"
                    >
                        <span class="sr-only">Fechar</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form wire:submit="save">
                    <div class="px-6 py-4 space-y-4">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-neutral-700 mb-1">Nome da receita</label>
                            <input type="text" id="name" wire:model="name" class="w-full p-2 rounded-md border-neutral-300 shadow-sm focus:border-caloriflix-500 focus:ring focus:ring-caloriflix-500 focus:ring-opacity-50" placeholder="Café da manhã, Almoço, Pré-treino, etc.">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="ingredients" class="block text-sm font-medium text-neutral-700 mb-1">Ingredientes</label>
                            <textarea id="ingredients" wire:model="ingredients" rows="3" class="w-full p-2 rounded-md border-neutral-300 shadow-sm focus:border-caloriflix-500 focus:ring focus:ring-caloriflix-500 focus:ring-opacity-50" placeholder="1 e 1/2 xícaras de chá de açúcar, 200g de farinha de trigo, 3 ovos, 1 colher de sopa de fermento em pó."></textarea>
                            @error('ingredients') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4 mb-4">
                            <div class="w-full sm:w-1/3">
                                <label for="preparation_time" class="block text-sm font-medium text-neutral-700 mb-1">Tempo de preparo (min)</label>
                                <input type="number" id="preparation_time" wire:model="preparation_time" min="0" class="w-full p-2 rounded-md border-neutral-300 focus:ring-caloriflix-500 focus:border-caloriflix-500 sm:text-sm">
                                @error('preparation_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full sm:w-1/3">
                                <label for="cooking_time" class="block text-sm font-medium text-neutral-700 mb-1">Tempo de cozimento (min)</label>
                                <input type="number" id="cooking_time" wire:model="cooking_time" min="0" class="w-full p-2 rounded-md border-neutral-300 focus:ring-caloriflix-500 focus:border-caloriflix-500 sm:text-sm">
                                @error('cooking_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full sm:w-1/3">
                                <label for="servings" class="block text-sm font-medium text-neutral-700 mb-1">Porções</label>
                                <input type="number" id="servings" wire:model="servings" min="1" class="w-full p-2 rounded-md border-neutral-300 focus:ring-caloriflix-500 focus:border-caloriflix-500 sm:text-sm">
                                @error('servings') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="instructions" class="block text-sm font-medium text-neutral-700 mb-1">Instruções</label>
                            <textarea id="instructions" wire:model="instructions" rows="3" class="w-full p-2 rounded-md border-neutral-300 shadow-sm focus:border-caloriflix-500 focus:ring focus:ring-caloriflix-500 focus:ring-opacity-50" placeholder="Descreva o passo a passo da receita..."></textarea>
                            @error('instructions') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 bg-neutral-50 border-t border-neutral-200 flex justify-end gap-3">
                        <button
                            type="button"
                            @click="$wire.closeModals()"
                            class="inline-flex justify-center rounded-md border border-neutral-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-neutral-700 hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-caloriflix-500 sm:text-sm"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-caloriflix-600 text-base font-medium text-white hover:bg-caloriflix-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-caloriflix-500 sm:text-sm"
                        >
                            Salvar receita
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
