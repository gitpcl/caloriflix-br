<div class="py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Relatórios</h1>
            
            <!-- Period navigation -->
            <div class="flex items-center space-x-4">
                <!-- Period selector -->
                <div class="inline-flex rounded-md shadow-sm">
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
                        class="relative -ml-px inline-flex items-center rounded-r-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10 {{ $period_type === 'monthly' ? 'bg-gray-200' : '' }}"
                    >
                        Mês
                    </button>
                </div>
                
                <!-- Date navigation -->
                <div class="flex items-center space-x-2">
                    <button 
                        wire:click="previousPeriod"
                        class="rounded-md p-1 text-gray-400 hover:text-gray-500 focus:outline-none"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    
                    <button 
                        wire:click="today"
                        class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                    >
                        Hoje
                    </button>
                    
                    <button 
                        wire:click="nextPeriod"
                        class="rounded-md p-1 text-gray-400 hover:text-gray-500 focus:outline-none"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
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
                        
                        <div class="h-16 w-16 text-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.027 1.028a4 4 0 00-2.171.102l-.47.156a4 4 0 01-2.53 0l-.563-.187a1.993 1.993 0 00-.114-.035l1.063-1.063A3 3 0 009 8.172z" clip-rule="evenodd" />
                            </svg>
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
