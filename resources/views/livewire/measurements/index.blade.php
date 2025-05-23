<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-200">Medidas Corporais</h2>
                <div>
                    <div class="inline-flex shadow-sm rounded-md">
                        <button 
                            type="button" 
                            wire:click="openAddModal"
                            class="inline-flex items-center p-2 bg-neutral-100 border border-transparent rounded-md font-semibold text-xs text-neutral-900 uppercase tracking-widest hover:bg-neutral-200 focus:bg-neutral-200 active:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-neutral-200 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Measurement Type Filter -->
            <div class="mb-6">
                <div class="flex flex-wrap gap-2">
                    <button 
                        wire:click="filterByType(null)" 
                        class="{{ is_null($selectedType) ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-neutral-100 text-neutral-800 dark:bg-neutral-700 dark:text-neutral-300' }} px-3 py-1 rounded-full text-sm font-medium"
                    >
                        Todos
                    </button>
                    
                    @foreach ($measurementTypes as $key => $label)
                        <button 
                            wire:click="filterByType('{{ $key }}')" 
                            class="{{ $selectedType === $key ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-neutral-100 text-neutral-800 dark:bg-neutral-700 dark:text-neutral-300' }} px-3 py-1 rounded-full text-sm font-medium"
                        >
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>
            
            @if($measurementsByDate->isEmpty())
                <div class="text-center py-12">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-neutral-100 dark:bg-neutral-700 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-neutral-900 dark:text-neutral-100">Você ainda não adicionou nenhuma medida.</h3>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                        Adicione uma medida para começar a monitorar seu progresso.
                    </p>
                    <div class="mt-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($measurementTypes as $key => $label)
                                <div class="p-4 bg-white dark:bg-neutral-700 rounded-lg shadow-sm">
                                    <div class="text-center">
                                        <h3 class="text-sm font-medium text-neutral-900 dark:text-neutral-100">{{ $label }}</h3>
                                        <button 
                                            type="button" 
                                            wire:click="openAddModal('{{ $key }}')"
                                            class="mt-2 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-300 hover:bg-green-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                        >
                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Adicionar
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else

                <!-- Measurements by Date -->
                <div class="space-y-8">
                    @foreach($measurementsByDate as $date => $measurements)
                        @php
                            $filteredMeasurements = $selectedType ? $measurements->where('type', $selectedType) : $measurements;
                        @endphp
                        
                        @if($filteredMeasurements->isNotEmpty())
                            <div class="bg-white dark:bg-neutral-700 rounded-lg shadow-sm overflow-hidden">
                                <div class="bg-neutral-50 dark:bg-neutral-800 px-4 py-2 border-b border-neutral-200 dark:border-neutral-600">
                                    <h3 class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                        {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                        <span class="ml-2 text-xs text-neutral-500 dark:text-neutral-400">
                                            {{ \Carbon\Carbon::parse($date)->diffForHumans() }}
                                        </span>
                                    </h3>
                                </div>
                                
                                <div class="p-0">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4">
                                        @foreach($filteredMeasurements as $measurement)
                                            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-lg p-4 relative group">
                                                <button 
                                                    wire:click="$dispatch('delete-measurement', { id: {{ $measurement->id }} })"
                                                    onclick="confirm('Tem certeza que deseja excluir esta medida?') || event.stopImmediatePropagation()"
                                                    class="absolute top-2 right-2 text-neutral-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                                
                                                <div class="text-center">
                                                    <h4 class="text-sm font-medium text-neutral-900 dark:text-neutral-100 mb-1">{{ $measurementTypes[$measurement->type] }}</h4>
                                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                                        {{ $measurement->value }} {{ $measurementUnits[$measurement->type] }}
                                                    </div>
                                                    @if($measurement->notes)
                                                        <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                                                            {{ $measurement->notes }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    
    <!-- Add Measurement Modal -->
    <div
        x-data="{ show: @entangle('showAddModal') }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
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
                <form wire:submit.prevent="saveMeasurement">
                    <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-neutral-900 dark:text-neutral-100">
                                    Adicionar {{ $measurementTypes[$type] ?? 'Nova Medida' }}
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="type" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Tipo de Medida</label>
                                        <select 
                                            wire:model="type" 
                                            id="type" 
                                            class="mt-1 p-2 block w-full rounded-md border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                        >
                                            @foreach($measurementTypes as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="value" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Valor {{ isset($measurementUnits[$type]) ? '(' . $measurementUnits[$type] . ')' : '' }}</label>
                                        <input 
                                            type="number" 
                                            step="0.01" 
                                            wire:model="value" 
                                            id="value" 
                                            class="mt-1 p-2 block w-full rounded-md border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                            placeholder="Ex: 70.5"
                                        >
                                        @error('value') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="notes" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Observações (opcional)</label>
                                        <textarea 
                                            wire:model="notes" 
                                            id="notes" 
                                            rows="2"
                                            class="mt-1 p-2 block w-full rounded-md border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                            placeholder="Observações adicionais..."
                                        ></textarea>
                                        @error('notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="date" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Data</label>
                                        <input 
                                            type="date" 
                                            wire:model="date" 
                                            id="date" 
                                            class="mt-1 p-2 block w-full rounded-md border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                        >
                                        @error('date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-neutral-50 dark:bg-neutral-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-300 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Salvar
                        </button>
                        <button 
                            type="button" 
                            wire:click="closeAddModal" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-800 text-base font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

</div>
