<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col space-y-4">
        <!-- Header with title and actions -->
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-medium text-neutral-900 dark:text-white">Alimentos cadastrados</h1>
            <div class="flex items-center space-x-2">
                <!-- Search input -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-neutral-400 lucide lucide-search">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar..." class="pl-10 pr-4 py-2 border border-neutral-200 dark:border-neutral-700 rounded-lg w-full dark:bg-neutral-700 dark:text-white text-sm">
                </div>
                
                <!-- Action buttons -->
                <button 
                    wire:click="openCreateFoodModal"
                    class="inline-flex items-center p-2 bg-neutral-100 border border-transparent rounded-md font-semibold text-xs text-neutral-900 uppercase tracking-widest hover:bg-neutral-200 focus:bg-neutral-200 active:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-neutral-200 transition ease-in-out duration-150"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/><path d="M12 5v14"/></svg>
                </button>
            </div>
        </div>
        
        <!-- Filters and Mass Actions Bar -->
        @if($selectionMode)
            <div class="flex items-center justify-between bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg px-4 py-3">
                <div class="flex items-center space-x-4">
                    <button wire:click="clearSelection" class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-neutral-800 dark:hover:text-neutral-200 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-1 lucide lucide-x">
                            <line x1="18" x2="6" y1="6" y2="18"/>
                            <line x1="6" x2="18" y1="6" y2="18"/>
                        </svg>
                        Cancelar
                    </button>
                    
                    <button wire:click="selectAll" class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-neutral-800 dark:hover:text-neutral-200 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-1 lucide lucide-check-circle">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        Selecionar todos
                    </button>
                </div>
                
                <button 
                    wire:click="massDelete" 
                    wire:confirm="Tem certeza que deseja excluir os alimentos selecionados?"
                    class="text-sm {{ count($selectedFoods) > 0 ? 'text-red-300 hover:text-red-400' : 'text-neutral-400 cursor-not-allowed' }} flex items-center"
                    {{ count($selectedFoods) == 0 ? 'disabled' : '' }}
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H9a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Excluir ({{ count($selectedFoods) }})
                </button>
            </div>
        @else
            <!-- Filter buttons -->
            <div class="flex items-center space-x-2 text-sm">
                
                <!-- Selection Mode Toggle Button -->
                <button 
                    wire:click="toggleSelectionMode" 
                    class="px-3 py-1 text-xs {{ $selectionMode ? 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200' : 'bg-white dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400' }} border border-neutral-200 dark:border-neutral-600 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-700 flex items-center"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Selecionar
                </button>
                
                <!-- Filter Dropdown -->
                <div class="relative" x-data="{ open: @entangle('showFilterDropdown') }">
                    <button 
                        wire:click="toggleFilterDropdown"
                        class="px-3 py-1 text-xs {{ $sourceFilter !== 'all' ? 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200' : 'bg-white dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400' }} border border-neutral-200 dark:border-neutral-600 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-700 flex items-center"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z" />
                        </svg>
                        Filtros
                        @if($sourceFilter !== 'all')
                            <span class="ml-1 text-xs bg-green-500 text-white rounded-full px-1">1</span>
                        @endif
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div 
                        x-show="open" 
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        @click.away="open = false"
                        class="absolute right-0 mt-2 w-64 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-600 rounded-lg shadow-lg z-50"
                    >
                        <div class="py-2">
                            <div class="px-4 py-2 text-xs font-medium text-neutral-500 dark:text-neutral-400 border-b border-neutral-200 dark:border-neutral-600">
                                Filtrar por origem
                            </div>
                            
                            <button 
                                wire:click="setSourceFilter('all')"
                                class="w-full px-4 py-2 text-left text-sm hover:bg-neutral-50 dark:hover:bg-neutral-700 flex items-center {{ $sourceFilter === 'all' ? 'text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20' : 'text-neutral-700 dark:text-neutral-300' }}"
                            >
                                @if($sourceFilter === 'all')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <div class="w-4 h-4 mr-2"></div>
                                @endif
                                Todos
                            </button>
                            
                            <button 
                                wire:click="setSourceFilter('manual')"
                                class="w-full px-4 py-2 text-left text-sm hover:bg-neutral-50 dark:hover:bg-neutral-700 flex items-center {{ $sourceFilter === 'manual' ? 'text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20' : 'text-neutral-700 dark:text-neutral-300' }}"
                            >
                                @if($sourceFilter === 'manual')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <div class="w-4 h-4 mr-2"></div>
                                @endif
                                Adicionados Manualmente
                            </button>
                            
                            <button 
                                wire:click="setSourceFilter('whatsapp')"
                                class="w-full px-4 py-2 text-left text-sm hover:bg-neutral-50 dark:hover:bg-neutral-700 flex items-center {{ $sourceFilter === 'whatsapp' ? 'text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20' : 'text-neutral-700 dark:text-neutral-300' }}"
                            >
                                @if($sourceFilter === 'whatsapp')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <div class="w-4 h-4 mr-2"></div>
                                @endif
                                Adicionados via WhatsApp
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Sort Dropdown -->
                <div class="relative" x-data="{ open: @entangle('showSortDropdown') }">
                    <button 
                        wire:click="toggleSortDropdown"
                        class="px-3 py-1 text-xs bg-white dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 border border-neutral-200 dark:border-neutral-600 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-700 flex items-center"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                        </svg>
                        Ordenar
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div 
                        x-show="open" 
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        @click.away="open = false"
                        class="absolute right-0 mt-2 w-64 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-600 rounded-lg shadow-lg z-50"
                    >
                        <div class="py-2">
                            <div class="px-4 py-2 text-xs font-medium text-neutral-500 dark:text-neutral-400 border-b border-neutral-200 dark:border-neutral-600">
                                Ordenar por
                            </div>
                            
                            <!-- Sort by Name -->
                            <button 
                                wire:click="setSortBy('name')"
                                class="w-full px-4 py-2 text-left text-sm hover:bg-neutral-50 dark:hover:bg-neutral-700 flex items-center {{ $sortBy === 'name' ? 'text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20' : 'text-neutral-700 dark:text-neutral-300' }}"
                            >
                                @if($sortBy === 'name')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <div class="w-4 h-4 mr-2"></div>
                                @endif
                                Nome
                            </button>
                            
                            <!-- Sort by Date -->
                            <button 
                                wire:click="setSortBy('created_at')"
                                class="w-full px-4 py-2 text-left text-sm hover:bg-neutral-50 dark:hover:bg-neutral-700 flex items-center {{ $sortBy === 'created_at' ? 'text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20' : 'text-neutral-700 dark:text-neutral-300' }}"
                            >
                                @if($sortBy === 'created_at')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <div class="w-4 h-4 mr-2"></div>
                                @endif
                                Data de Registro
                            </button>
                            
                            <div class="border-t border-neutral-200 dark:border-neutral-600 my-2"></div>
                            
                            <!-- Sort Direction -->
                            <button 
                                wire:click="setSortDirection('asc')"
                                class="w-full px-4 py-2 text-left text-sm hover:bg-neutral-50 dark:hover:bg-neutral-700 flex items-center {{ $sortDirection === 'asc' ? 'text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20' : 'text-neutral-700 dark:text-neutral-300' }}"
                            >
                                @if($sortDirection === 'asc')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <div class="w-4 h-4 mr-2"></div>
                                @endif
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                                Ordem Crescente
                            </button>
                            
                            <button 
                                wire:click="setSortDirection('desc')"
                                class="w-full px-4 py-2 text-left text-sm hover:bg-neutral-50 dark:hover:bg-neutral-700 flex items-center {{ $sortDirection === 'desc' ? 'text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20' : 'text-neutral-700 dark:text-neutral-300' }}"
                            >
                                @if($sortDirection === 'desc')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <div class="w-4 h-4 mr-2"></div>
                                @endif
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                                Ordem Decrescente
                            </button>
                        </div>
                    </div>
                </div>
                
                {{-- <button class="px-3 py-1 text-xs bg-white dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 border border-neutral-200 dark:border-neutral-600 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Atualizar
                </button> --}}
                
                <button 
                    wire:click="exportCsv"
                    class="px-3 py-1 text-xs bg-white dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 border border-neutral-200 dark:border-neutral-600 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-700 flex items-center"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download-icon lucide-download h-4 w-4 mr-1"><path d="M12 15V3"/><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/></svg>
                    Baixar CSV
                </button>
            </div>
        @endif
        
        <!-- Food List -->
        <div class="grid grid-cols-1 gap-4">
            @if (session()->has('message'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800">
                    {{ session('message') }}
                </div>
            @endif
            
            @forelse ($foods as $food)
                <div class="bg-white dark:bg-neutral-800 shadow-sm rounded-md border border-neutral-100 dark:border-neutral-700 overflow-hidden cursor-pointer" wire:click="openFoodEditModal({{ $food->id }})">
                    <div class="px-4 py-3 flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <!-- Checkbox for mass selection -->
                            @if($selectionMode)
                                <div class="flex-shrink-0" wire:click.stop>
                                    <input 
                                        type="checkbox" 
                                        wire:model.live="selectedFoods" 
                                        value="{{ $food->id }}" 
                                        class="h-4 w-4 text-green-600 border-neutral-300 dark:border-neutral-600 rounded focus:ring-green-500"
                                    >
                                </div>
                            @endif
                            
                            <!-- Food icon based on type -->
                            <div class="flex-shrink-0">
                                <div class="bg-green-100 rounded p-2"><span class="text-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-utensils-icon lucide-utensils text-green-600"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>
                                </span></div>
                            </div>
                            
                            <!-- Food details -->
                            <div>
                                <h3 class="text-sm font-medium text-neutral-900 dark:text-white leading-tight">
                                    {{ floor($food->quantity) }}{{ strtolower($food->unit) == 'ml' || strtolower($food->unit) == 'gramas' ? 'g' : ' ' . $food->unit }} {{ $food->name }}
                                </h3>
                                <div class="mt-0.5">
                                    <div class="text-xs text-neutral-600 dark:text-neutral-400 leading-tight">
                                        {{ floor($food->protein) }}g prot · {{ floor($food->fat) }}g gord · {{ floor($food->carbohydrate) }}g carb · {{ floor($food->calories) }}kcal
                                    </div>
                                    <a href="#" class="text-xs text-neutral-600 dark:text-neutral-400 underline">Tabela nutricional</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Add button -->
                        <button 
                            wire:click.stop="openFoodDetailModal({{ $food->id }})" 
                            class="text-xs p-2 flex-shrink-0 rounded-md bg-neutral-100 dark:bg-neutral-700 border border-neutral-200 dark:border-neutral-600 flex items-center justify-center text-neutral-600 dark:text-neutral-300 hover:text-neutral-800 hover:border-neutral-300 focus:outline-none space-x-2"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Adicionar à refeição</span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-neutral-100 dark:bg-neutral-700 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-neutral-900 dark:text-neutral-100">Nenhum alimento encontrado</h3>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                        Adicione um alimento para começar.
                    </p>
                    <div class="mt-6">
                        <button 
                            type="button" 
                            wire:click="openCreateFoodModal"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-300 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Adicionar primeiro alimento
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Create Food Modal -->    
    <div 
        x-data="{ show: @entangle('showCreateFoodModal') }" 
        x-show="show" 
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto" 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div 
                x-show="show" 
                x-transition:enter="ease-out duration-300" 
                x-transition:enter-start="opacity-0" 
                x-transition:enter-end="opacity-100" 
                x-transition:leave="ease-in duration-200" 
                x-transition:leave-start="opacity-100" 
                x-transition:leave-end="opacity-0" 
                class="fixed inset-0 transition-opacity"
            >
                <div class="absolute inset-0 bg-neutral-500/40 dark:bg-neutral-900"></div>
            </div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
            
            <div 
                x-show="show" 
                x-transition:enter="ease-out duration-300" 
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                x-transition:leave="ease-in duration-200" 
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            >
                <form wire:submit.prevent="save">
                    <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-neutral-900 dark:text-neutral-100">
                                    Adicionar Alimento
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Nome</label>
                                        <input 
                                            type="text" 
                                            wire:model="name" 
                                            id="name" 
                                            placeholder="Arroz, Feijão, etc." 
                                            class="mt-1 p-2 block w-full rounded-md border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                        >
                                        @error('name') <span class="text-red-300 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="quantity" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Quantidade</label>
                                        <div class="mt-1 flex rounded-md">
                                            <input 
                                                type="text" 
                                                wire:model="quantity" 
                                                id="quantity" 
                                                class="p-2 block w-full rounded-l-md border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                            >
                                            <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-neutral-300 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-700 text-neutral-500 dark:text-neutral-300 text-sm">
                                                g
                                            </span>
                                        </div>
                                        @error('quantity') <span class="text-red-300 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="unit" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Unidade</label>
                                        <select 
                                            wire:model="unit" 
                                            id="unit" 
                                            class="mt-1 p-2 block w-full rounded-md border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                        >
                                            <option value="">Selecione uma unidade</option>
                                            <option value="gramas">Gramas</option>
                                            <option value="ml">Mililitros</option>
                                            <option value="unidade">Unidade</option>
                                            <option value="fatia">Fatia</option>
                                            <option value="colher">Colher</option>
                                            <option value="xícara">Xícara</option>
                                        </select>
                                        @error('unit') <span class="text-red-300 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="protein" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Proteína</label>
                                        <div class="mt-1 flex rounded-md">
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                wire:model="protein" 
                                                id="protein" 
                                                class="p-2 block w-full rounded-l-md border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                            >
                                            <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-neutral-300 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-700 text-neutral-500 dark:text-neutral-300 text-sm">
                                                g
                                            </span>
                                        </div>
                                        @error('protein') <span class="text-red-300 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="fat" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Gordura</label>
                                        <div class="mt-1 flex rounded-md">
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                wire:model="fat" 
                                                id="fat" 
                                                class="p-2 block w-full rounded-l-md border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                            >
                                            <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-neutral-300 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-700 text-neutral-500 dark:text-neutral-300 text-sm">
                                                g
                                            </span>
                                        </div>
                                        @error('fat') <span class="text-red-300 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="carbohydrate" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Carboidrato</label>
                                        <div class="mt-1 flex rounded-md">
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                wire:model="carbohydrate" 
                                                id="carbohydrate" 
                                                class="p-2 block w-full rounded-l-md border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                            >
                                            <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-neutral-300 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-700 text-neutral-500 dark:text-neutral-300 text-sm">
                                                g
                                            </span>
                                        </div>
                                        @error('carbohydrate') <span class="text-red-300 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="calories" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Calorias</label>
                                        <div class="mt-1 flex rounded-md">
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                wire:model="calories" 
                                                id="calories" 
                                                class="p-2 block w-full rounded-l-md border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                            >
                                            <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-neutral-300 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-700 text-neutral-500 dark:text-neutral-300 text-sm">
                                                kcal
                                            </span>
                                        </div>
                                        @error('calories') <span class="text-red-300 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-neutral-50 dark:bg-neutral-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Salvar
                        </button>
                        <button 
                            type="button" 
                            wire:click="closeModals" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-800 text-base font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Food Detail Modal -->
    @if ($selectedFood)
    <div 
        x-data="{ show: @entangle('showFoodDetailModal') }" 
        x-show="show" 
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto" 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div 
                x-show="show" 
                x-transition:enter="ease-out duration-300" 
                x-transition:enter-start="opacity-0" 
                x-transition:enter-end="opacity-100" 
                x-transition:leave="ease-in duration-200" 
                x-transition:leave-start="opacity-100" 
                x-transition:leave-end="opacity-0" 
                class="fixed inset-0 transition-opacity"
            >
                <div class="absolute inset-0 bg-neutral-500/40 dark:bg-neutral-900"></div>
            </div>

            <div 
                @click.outside="show = false"
                class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full"
                role="dialog" 
                aria-modal="true" 
                aria-labelledby="modal-headline"
            >
                
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
                        <h2 class="text-xl font-medium text-neutral-900 dark:text-neutral-100 mb-1">{{ floor($selectedFood->quantity) }}g {{ $selectedFood->name }}</h2>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ floor($selectedFood->protein) }}g prot · {{ floor($selectedFood->fat) }}g gord · {{ floor($selectedFood->carbohydrate) }}g carb · {{ floor($selectedFood->calories) }}kcal</p>
                    </div>
                    
                    <!-- Quantity Adjustment -->
                    <div class="mb-6">
                        <label class="block text-center text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Alterar quantidade:</label>
                        <div class="flex items-center justify-center">
                            <button 
                                wire:click="updateFoodQuantity({{ $foodQuantity - 5 }})" 
                                class="px-3 py-1 bg-neutral-200 dark:bg-neutral-700 rounded-l text-neutral-700 dark:text-neutral-300 hover:bg-neutral-300 dark:hover:bg-neutral-600"
                            >
                                –
                            </button>
                            <input 
                                type="number" 
                                wire:model.debounce.500ms="foodQuantity" 
                                min="5" 
                                class="w-16 py-1 text-center border-t border-b border-neutral-300 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white"
                            >
                            <button 
                                wire:click="updateFoodQuantity({{ $foodQuantity + 5 }})" 
                                class="px-3 py-1 bg-neutral-200 dark:bg-neutral-700 rounded-r text-neutral-700 dark:text-neutral-300 hover:bg-neutral-300 dark:hover:bg-neutral-600"
                            >
                                +
                            </button>
                        </div>
                    </div>
                    
                    <!-- Used Recently Option -->
                    <div class="flex items-center justify-center mb-4">
                        <input id="recently-used" type="checkbox" wire:model="recentlyUsed" class="h-4 w-4 text-green-600 border-neutral-300 rounded">
                        <label for="recently-used" class="ml-2 block text-sm text-neutral-600 dark:text-neutral-400">OK, usados recentemente</label>
                    </div>
                    
                    <!-- No Records Message -->
                    <div class="text-center text-sm text-neutral-500 dark:text-neutral-400 mb-6">
                        Sem registros
                    </div>
                    
                    <!-- Add to Meal Button -->
                    <div class="mb-8">
                        <div x-data="{ open: false }" class="relative">
                            <button 
                                @click="open = !open" 
                                type="button" 
                                class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
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
                        <h3 class="text-sm font-medium text-neutral-800 dark:text-neutral-200 mb-3">Registros do alimento: 1</h3>
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
    <div 
        x-data="{ show: @entangle('showFoodEditModal') }" 
        x-show="show" 
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto" 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div 
                x-show="show" 
                x-transition:enter="ease-out duration-300" 
                x-transition:enter-start="opacity-0" 
                x-transition:enter-end="opacity-100" 
                x-transition:leave="ease-in duration-200" 
                x-transition:leave-start="opacity-100" 
                x-transition:leave-end="opacity-0" 
                class="fixed inset-0 transition-opacity"
            >
                <div class="absolute inset-0 bg-neutral-500/40 dark:bg-neutral-900"></div>
            </div>

            <div 
                @click.outside="show = false"
                class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full"
                role="dialog" 
                aria-modal="true" 
                aria-labelledby="modal-headline"
            >
                
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
                        <div class="w-12 h-12 bg-neutral-200 dark:bg-neutral-700 rounded mr-3 flex items-center justify-center overflow-hidden">
                            <img src="https://via.placeholder.com/50" alt="{{ $selectedFood->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="text-center">
                            <h2 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">{{ $quantity }}g {{ $name }}</h2>
                            <div class="flex items-center text-sm text-neutral-500">
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
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Name</label>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        wire:model="name" 
                                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    >
                                    <button type="button" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-red-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">Adicionado via WhatsApp</div>
                                @error('name') <span class="text-red-300 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Quantity Field -->
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Quantidade</label>
                                <div class="flex">
                                    <input 
                                        type="number" 
                                        wire:model="quantity" 
                                        step="0.1"
                                        class="w-full border border-gray-300 rounded-l px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    >
                                    <span class="inline-flex items-center px-3 rounded-r border border-l-0 border-gray-300 bg-gray-50 text-gray-500 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">g</span>
                                </div>
                                @error('quantity') <span class="text-red-300 text-xs">{{ $message }}</span> @enderror
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
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Proteína</label>
                                        <div class="flex">
                                            <input 
                                                type="number" 
                                                wire:model="protein"
                                                step="0.1"
                                                class="w-full border border-gray-300 rounded-l px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            >
                                            <span class="inline-flex items-center px-2 py-2 rounded-r border border-l-0 border-gray-300 bg-gray-50 text-gray-500 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">gramas</span>
                                        </div>
                                        @error('protein') <span class="text-red-300 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Gordura</label>
                                        <div class="flex">
                                            <input 
                                                type="number" 
                                                wire:model="fat"
                                                step="0.1"
                                                class="w-full border border-gray-300 rounded-l px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            >
                                            <span class="inline-flex items-center px-2 py-2 rounded-r border border-l-0 border-gray-300 bg-gray-50 text-gray-500 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">gramas</span>
                                        </div>
                                        @error('fat') <span class="text-red-300 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Carboidrato</label>
                                        <div class="flex">
                                            <input 
                                                type="number" 
                                                wire:model="carbohydrate"
                                                step="0.1"
                                                class="w-full border border-gray-300 rounded-l px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            >
                                            <span class="inline-flex items-center px-2 py-2 rounded-r border border-l-0 border-gray-300 bg-gray-50 text-gray-500 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">gramas</span>
                                        </div>
                                        @error('carbohydrate') <span class="text-red-300 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Fibras</label>
                                        <div class="flex">
                                            <input 
                                                type="number" 
                                                wire:model="fiber"
                                                step="0.1"
                                                class="w-full border border-gray-300 rounded-l px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            >
                                            <span class="inline-flex items-center px-2 py-2 rounded-r border border-l-0 border-gray-300 bg-gray-50 text-gray-500 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">gramas</span>
                                        </div>
                                        @error('fiber') <span class="text-red-300 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Calorias</label>
                                        <div class="flex">
                                            <input 
                                                type="number" 
                                                wire:model="calories"
                                                step="1"
                                                class="w-full border border-gray-300 rounded-l px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            >
                                            <span class="inline-flex items-center px-2 py-2 rounded-r border border-l-0 border-gray-300 bg-gray-50 text-gray-500 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">kcal</span>
                                        </div>
                                        @error('calories') <span class="text-red-300 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="pt-4 flex justify-center">
                                <button type="button" wire:click="delete({{ $selectedFood->id }})" class="flex items-center px-4 py-2 bg-neutral-200 dark:bg-neutral-700 text-neutral-800 dark:text-neutral-200 rounded-md hover:bg-neutral-300 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-neutral-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    <span>Excluir</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0111 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button type="submit" class="flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
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
                        <h3 class="text-sm font-medium text-neutral-800 dark:text-neutral-200 mb-3">Registros do alimento: 1</h3>
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
