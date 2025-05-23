<div class="py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Relatórios</h1>
            
            <!-- Period navigation -->
            <div class="flex items-center space-x-4">
                <!-- Period selector -->
                <div class="inline-flex rounded-md shadow-sm relative">
                    <button 
                        wire:click="changePeriodType('daily')"
                        type="button" 
                        class="relative inline-flex items-center rounded-l-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10 {{ $period_type === 'daily' ? 'bg-gray-200' : '' }}"
                    >
                        Dia
                    </button>
                    <button 
                        wire:click="changePeriodType('weekly')"
                        type="button" 
                        class="relative -ml-px inline-flex items-center bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10 {{ $period_type === 'weekly' ? 'bg-gray-200' : '' }}"
                    >
                        Sem
                    </button>
                    <button 
                        wire:click="changePeriodType('monthly')"
                        type="button" 
                        class="relative -ml-px inline-flex items-center bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10 {{ $period_type === 'monthly' ? 'bg-gray-200' : '' }}"
                    >
                        Mês
                    </button>
                    <button 
                        wire:click="changePeriodType('custom')"
                        type="button" 
                        class="relative -ml-px inline-flex items-center rounded-r-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10 {{ $period_type === 'custom' ? 'bg-blue-100 text-blue-700 border-blue-300' : '' }}"
                        id="personalizado-button"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        Personalizado
                    </button>
                    
                    <!-- Custom Date Range Modal positioned relative to button -->
                    @if($show_custom_modal)
                    <!-- Background overlay -->
                    <div class="fixed inset-0 z-40" wire:click="closeCustomModal"></div>
                    
                    <div class="absolute top-full mt-2 right-0 w-96 bg-white rounded-xl shadow-2xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700 z-50">
                        <!-- Content -->
                        <div class="p-6">
                            <!-- Absolute/Relative Toggle -->
                            <div class="flex items-center justify-center mb-6">
                                <div class="inline-flex rounded-lg border border-gray-200 p-1 bg-gray-50">
                                    <button type="button" class="relative inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-medium text-gray-900 shadow-sm ring-1 ring-gray-300 focus:z-10">
                                        Absoluto
                                    </button>
                                    <button type="button" class="relative inline-flex items-center rounded-md px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-900 focus:z-10">
                                        Relativo
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Date Inputs -->
                            <div class="space-y-4 mb-6">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Começa em
                                    </label>
                                    <input 
                                        type="datetime-local" 
                                        id="start_date"
                                        wire:model="custom_start_date"
                                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="05/22/2025, 03:20 AM"
                                    />
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Termina em
                                    </label>
                                    <input 
                                        type="datetime-local" 
                                        id="end_date"
                                        wire:model="custom_end_date"
                                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="05/23/2025, 03:20 AM"
                                    />
                                </div>
                            </div>
                            
                            <!-- Quick Period Buttons -->
                            <div class="flex gap-2 mb-6">
                                <button 
                                    wire:click="setQuickPeriod('24h')"
                                    type="button" 
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    24H
                                </button>
                                <button 
                                    wire:click="setQuickPeriod('3d')"
                                    type="button" 
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                                >
                                    3D
                                </button>
                                <button 
                                    wire:click="setQuickPeriod('7d')"
                                    type="button" 
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                                >
                                    7D
                                </button>
                                <button 
                                    wire:click="setQuickPeriod('1m')"
                                    type="button" 
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                                >
                                    1M
                                </button>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-3">
                                <button 
                                    wire:click="closeCustomModal"
                                    type="button" 
                                    class="flex-1 inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                                >
                                    Cancelar
                                </button>
                                <button 
                                    wire:click="applyCustomRange"
                                    type="button" 
                                    class="flex-1 inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium text-white bg-black rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                                >
                                    Salvar
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Macro nutrition section -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Média de macros</h2>
            
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Protein Box -->
                <div class="relative overflow-hidden rounded-lg bg-white shadow dark:bg-zinc-700 dark:shadow-zinc-700/20">
                    <div class="p-5">
                        <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">Proteína ingerida em</div>
                        <div class="mt-1 flex items-baseline justify-between">
                            <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $nutrient_macros['protein'] }}g</div>
                            <div class="inline-flex items-baseline rounded-full bg-green-100 px-2.5 py-0.5 text-sm font-medium text-green-800 dark:bg-green-800/20 dark:text-green-500">
                                {{ $this->getProteinPercentage() }}%
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            Item: valores médios por data
                        </div>
                    </div>
                </div>
                
                <!-- Carbs Box -->
                <div class="relative overflow-hidden rounded-lg bg-white shadow dark:bg-zinc-700 dark:shadow-zinc-700/20">
                    <div class="p-5">
                        <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">Carboidratos por dia</div>
                        <div class="mt-1 flex items-baseline justify-between">
                            <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $nutrient_macros['carbs'] }}g</div>
                            <div class="inline-flex items-baseline rounded-full bg-yellow-100 px-2.5 py-0.5 text-sm font-medium text-yellow-800 dark:bg-yellow-800/20 dark:text-yellow-500">
                                {{ $this->getCarbsPercentage() }}%
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            Item: valores médios por data
                        </div>
                    </div>
                </div>
                
                <!-- Fats Box -->
                <div class="relative overflow-hidden rounded-lg bg-white shadow dark:bg-zinc-700 dark:shadow-zinc-700/20">
                    <div class="p-5">
                        <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">Gordura ingerida por dia</div>
                        <div class="mt-1 flex items-baseline justify-between">
                            <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $nutrient_macros['fat'] }}g</div>
                            <div class="inline-flex items-baseline rounded-full bg-blue-100 px-2.5 py-0.5 text-sm font-medium text-blue-800 dark:bg-blue-800/20 dark:text-blue-500">
                                {{ $this->getFatPercentage() }}%
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            Item: valores médios por data
                        </div>
                    </div>
                </div>
                
                <!-- Calories Box -->
                <div class="relative overflow-hidden rounded-lg bg-white shadow dark:bg-zinc-700 dark:shadow-zinc-700/20">
                    <div class="p-5">
                        <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">Calorias ingeridas por dia</div>
                        <div class="mt-1 flex items-baseline justify-between">
                            <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $nutrient_macros['calories'] }} kcal</div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            Item: valores médios por data
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Water consumption section -->
        <div class="mt-8">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Consumo de Água <span class="ml-1 inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">novo</span></h2>
            </div>
            
            <div class="mt-4 overflow-hidden rounded-lg bg-white shadow dark:bg-zinc-700 dark:shadow-zinc-700/20">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">Consumo Total</div>
                            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $water_consumption }} ml</div>
                        </div>
                        
                        <div class="h-6 w-6 text-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-glass-water-icon lucide-glass-water"><path d="M5.116 4.104A1 1 0 0 1 6.11 3h11.78a1 1 0 0 1 .994 1.105L17.19 20.21A2 2 0 0 1 15.2 22H8.8a2 2 0 0 1-2-1.79z"/><path d="M6 12a5 5 0 0 1 6 0 5 5 0 0 0 6 0"/></svg>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">Tipo</div>
                            <div class="flex items-center space-x-1">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tempo</span>
                                <span class="inline-flex h-4 w-4 items-center justify-center rounded-full bg-gray-200 text-xs font-semibold text-gray-800 dark:bg-gray-700 dark:text-gray-300">?</span>
                            </div>
                        </div>
                        
                        <div class="mt-2 flex items-center justify-between">
                            <div class="inline-flex items-center space-x-1">
                                <span class="inline-block h-2.5 w-2.5 rounded-full bg-blue-600"></span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tempo</span>
                            </div>
                            
                            <div class="inline-flex items-center space-x-1">
                                <span class="inline-block h-2.5 w-2.5 rounded-full bg-blue-300"></span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Água</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Placeholder for future features -->

        
        <!-- Empty states for additional reports -->
        <div class="my-8 text-center text-sm text-gray-500 dark:text-gray-400">
            Sem dados encontrados para outros relatórios
        </div>
    </div>
</div>
