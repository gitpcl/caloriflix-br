<div class="py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Relatórios</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $this->getPeriodDisplayText() }}</p>
            </div>
            
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
                        class="relative -ml-px inline-flex items-center rounded-r-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10 {{ $period_type === 'custom' ? 'bg-caloriflix-100 text-caloriflix-700 border-caloriflix-300' : '' }}"
                        id="personalizado-button"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1 lucide lucide-calendar">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                            <line x1="16" x2="16" y1="2" y2="6"/>
                            <line x1="8" x2="8" y1="2" y2="6"/>
                            <line x1="3" x2="21" y1="10" y2="10"/>
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
                                    <button 
                                        wire:click="setDateMode('absolute')"
                                        type="button" 
                                        class="relative inline-flex items-center rounded-md px-4 py-2 text-sm font-medium focus:z-10 {{ $date_mode === 'absolute' ? 'bg-white text-gray-900 shadow-sm ring-1 ring-gray-300' : 'text-gray-500 hover:text-gray-900' }}"
                                    >
                                        Absoluto
                                    </button>
                                    <button 
                                        wire:click="setDateMode('relative')"
                                        type="button" 
                                        class="relative inline-flex items-center rounded-md px-4 py-2 text-sm font-medium focus:z-10 {{ $date_mode === 'relative' ? 'bg-white text-gray-900 shadow-sm ring-1 ring-gray-300' : 'text-gray-500 hover:text-gray-900' }}"
                                    >
                                        Relativo
                                    </button>
                                </div>
                            </div>
                            
                            @if($date_mode === 'absolute')
                            <!-- Absolute Date Inputs -->
                            <div class="space-y-4 mb-6">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Começa em
                                    </label>
                                    <input 
                                        type="datetime-local" 
                                        id="start_date"
                                        wire:model="custom_start_date"
                                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 focus:border-caloriflix-500 focus:ring-caloriflix-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
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
                                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 focus:border-caloriflix-500 focus:ring-caloriflix-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="05/23/2025, 03:20 AM"
                                    />
                                </div>
                            </div>
                            @endif
                            
                            @if($date_mode === 'absolute')
                            <!-- Quick Period Buttons -->
                            <div class="flex gap-2 mb-6">
                                <button 
                                    wire:click="setQuickPeriod('24h')"
                                    type="button" 
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-caloriflix-500 hover:bg-caloriflix-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-caloriflix-500"
                                >
                                    24H
                                </button>
                                <button 
                                    wire:click="setQuickPeriod('3d')"
                                    type="button" 
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-caloriflix-500"
                                >
                                    3D
                                </button>
                                <button 
                                    wire:click="setQuickPeriod('7d')"
                                    type="button" 
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-caloriflix-500"
                                >
                                    7D
                                </button>
                                <button 
                                    wire:click="setQuickPeriod('1m')"
                                    type="button" 
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-caloriflix-500"
                                >
                                    1M
                                </button>
                            </div>
                            @endif
                            
                            @if($date_mode === 'relative')
                            <!-- Relative Date Inputs -->
                            <div class="space-y-4 mb-6">
                                <div class="flex gap-3">
                                    <div class="flex-1">
                                        <label for="relative_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Quantidade
                                        </label>
                                        <input 
                                            type="number" 
                                            id="relative_amount"
                                            wire:model="relative_amount"
                                            min="1"
                                            max="365"
                                            class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 focus:border-caloriflix-500 focus:ring-caloriflix-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        />
                                    </div>
                                    <div class="flex-1">
                                        <label for="relative_unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Unidade
                                        </label>
                                        <select 
                                            id="relative_unit"
                                            wire:model="relative_unit"
                                            class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 focus:border-caloriflix-500 focus:ring-caloriflix-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        >
                                            <option value="days">Dias</option>
                                            <option value="weeks">Semanas</option>
                                            <option value="months">Meses</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Mostrará dados dos últimos {{ $relative_amount }} 
                                    @if($relative_unit === 'days')
                                        {{ $relative_amount == 1 ? 'dia' : 'dias' }}
                                    @elseif($relative_unit === 'weeks')
                                        {{ $relative_amount == 1 ? 'semana' : 'semanas' }}
                                    @else
                                        {{ $relative_amount == 1 ? 'mês' : 'meses' }}
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-3">
                                <button 
                                    wire:click="closeCustomModal"
                                    type="button" 
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-caloriflix-500"
                                >
                                    Cancelar
                                </button>
                                <button 
                                    wire:click="applyCustomRange"
                                    type="button" 
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                                >
                                    Salvar
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Water Registration Modal -->
            @if($show_water_modal)
            <!-- Background overlay -->
            <div class="fixed inset-0 z-40 bg-black/40" wire:click="closeWaterModal"></div>
            
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="w-full max-w-md bg-white rounded-xl shadow-2xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <!-- Header -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Registrar Consumo de Água</h3>
                        <button 
                            wire:click="closeWaterModal"
                            type="button" 
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                                <line x1="18" x2="6" y1="6" y2="18"/>
                                <line x1="6" x2="18" y1="6" y2="18"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Amount Input -->
                            <div>
                                <label for="water_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Quantidade (ml)
                                </label>
                                <input 
                                    type="number" 
                                    id="water_amount"
                                    wire:model="water_amount"
                                    min="1"
                                    max="5000"
                                    step="50"
                                    class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 focus:border-caloriflix-500 focus:ring-caloriflix-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                />
                                @error('water_amount') 
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                                @enderror
                            </div>
                            
                            <!-- Date Input -->
                            <div>
                                <label for="water_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Data
                                </label>
                                <input 
                                    type="date" 
                                    id="water_date"
                                    wire:model="water_date"
                                    class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 focus:border-caloriflix-500 focus:ring-caloriflix-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                />
                                @error('water_date') 
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                                @enderror
                            </div>
                            
                            <!-- Quick Amount Buttons -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Quantidades Rápidas
                                </label>
                                <div class="grid grid-cols-4 gap-2">
                                    <button 
                                        wire:click="$set('water_amount', 200)"
                                        type="button" 
                                        class="px-3 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                                    >
                                        200ml
                                    </button>
                                    <button 
                                        wire:click="$set('water_amount', 250)"
                                        type="button" 
                                        class="px-3 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                                    >
                                        250ml
                                    </button>
                                    <button 
                                        wire:click="$set('water_amount', 500)"
                                        type="button" 
                                        class="px-3 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                                    >
                                        500ml
                                    </button>
                                    <button 
                                        wire:click="$set('water_amount', 1000)"
                                        type="button" 
                                        class="px-3 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                                    >
                                        1L
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex gap-3 mt-6">
                            <button 
                                wire:click="closeWaterModal"
                                type="button" 
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-caloriflix-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600"
                            >
                                Cancelar
                            </button>
                            <button 
                                wire:click="addWaterEntry"
                                type="button" 
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-caloriflix-600 hover:bg-caloriflix-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-caloriflix-500"
                            >
                                Adicionar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Macro nutrition section -->
    <div class="mt-8 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Média de macros</h2>
        
        @if($nutrient_macros['calories'] > 0)
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Protein Box -->
                <div class="relative overflow-hidden rounded-lg bg-white shadow dark:bg-zinc-700 dark:shadow-zinc-700/20">
                    <div class="p-5">
                        <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">Proteína</div>
                        <div class="mt-1 flex items-baseline justify-between">
                            <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $nutrient_macros['protein'] }}g</div>
                            <div class="inline-flex items-baseline rounded-full bg-caloriflix-100 px-2.5 py-0.5 text-sm font-medium text-caloriflix-800 dark:bg-caloriflix-800/20 dark:text-caloriflix-500">
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
                        <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">
                            Carboidratos
                        </div>
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
                        <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">Gordura</div>
                        <div class="mt-1 flex items-baseline justify-between">
                            <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $nutrient_macros['fat'] }}g</div>
                            <div class="inline-flex items-baseline rounded-full bg-caloriflix-100 px-2.5 py-0.5 text-sm font-medium text-caloriflix-800 dark:bg-caloriflix-800/20 dark:text-caloriflix-500">
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
                        <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">Déficit calórico total</div>
                        <div class="mt-1 flex items-baseline justify-between">
                            <div class="text-2xl font-semibold {{ $this->getCaloricDeficitColor() }}">
                                {{ $this->getCaloricDeficitFormatted() }}
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            Meta: {{ number_format($daily_calorie_goal) }} kcal/dia
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- No data message -->
            <div class="mt-4 rounded-lg bg-gray-50 p-6 dark:bg-gray-800">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-gray-400 lucide lucide-bar-chart-3">
                        <path d="M3 3v18h18"/>
                        <path d="M18 17V9"/>
                        <path d="M13 17V5"/>
                        <path d="M8 17v-3"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum dado de refeição encontrado</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Não há refeições registradas para o período selecionado. Comece adicionando suas refeições para ver os dados nutricionais.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('meals.index') }}" class="inline-flex items-center rounded-md bg-caloriflix-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-caloriflix-500 focus:outline-2 focus:outline-offset-2 focus:outline-caloriflix-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="-ml-0.5 mr-1.5 h-5 w-5 lucide lucide-plus">
                                <line x1="12" x2="12" y1="5" y2="19"/>
<line x1="5" x2="19" y1="12" y2="12"/>
                            </svg>
                            Adicionar Refeição
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Water consumption section -->
    <div class="mt-8 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Consumo de Água <span class="ml-1 inline-flex items-center rounded-md bg-caloriflix-50 px-2 py-1 text-xs font-medium text-caloriflix-700 ring-1 ring-inset ring-caloriflix-600/20 dark:bg-caloriflix-500/10 dark:text-caloriflix-400 dark:ring-caloriflix-500/20">novo</span></h2>
        </div>
        
        @if($water_consumption > 0)
            <div class="mt-4 overflow-hidden rounded-lg bg-white shadow dark:bg-zinc-700 dark:shadow-zinc-700/20">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">
                                @if($period_type === 'daily')
                                    Consumo do Dia
                                @else
                                    Média Diária
                                @endif
                            </div>
                            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($water_consumption, 0) }} ml</div>
                            @if($period_type !== 'daily')
                                <div class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                                    @switch($period_type)
                                        @case('weekly')
                                            Baseado na semana selecionada
                                            @break
                                        @case('monthly')
                                            Baseado no mês selecionado
                                            @break
                                        @case('custom')
                                            Baseado no período personalizado
                                            @break
                                    @endswitch
                                </div>
                            @endif
                        </div>
                        
                        <div class=" bg-caloriflix-50 p-2 rounded-md text-caloriflix-500 cursor-pointer animate-pulse" wire:click="openWaterModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-glass-water-icon lucide-glass-water"><path d="M5.116 4.104A1 1 0 0 1 6.11 3h11.78a1 1 0 0 1 .994 1.105L17.19 20.21A2 2 0 0 1 15.2 22H8.8a2 2 0 0 1-2-1.79z"/><path d="M6 12a5 5 0 0 1 6 0 5 5 0 0 0 6 0"/></svg>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-500 dark:text-zinc-400">Status</div>
                            <div class="flex items-center space-x-1">
                                @php
                                    $dailyGoal = 2000; // 2L daily goal
                                    $percentage = min(100, ($water_consumption / $dailyGoal) * 100);
                                    $status = $percentage >= 100 ? 'Excelente' : ($percentage >= 75 ? 'Bom' : ($percentage >= 50 ? 'Regular' : 'Baixo'));
                                    $statusColor = $percentage >= 100 ? 'text-caloriflix-600' : ($percentage >= 75 ? 'text-caloriflix-600' : ($percentage >= 50 ? 'text-yellow-600' : 'text-red-600'));
                                @endphp
                                <span class="text-sm font-medium {{ $statusColor }}">{{ $status }}</span>
                                <span class="inline-flex h-4 w-4 items-center justify-center rounded-full bg-gray-200 text-xs font-semibold text-gray-800 dark:bg-gray-700 dark:text-gray-300" title="Meta diária: {{ number_format($dailyGoal) }}ml">?</span>
                            </div>
                        </div>
                        
                        <!-- Progress bar -->
                        <div class="mt-3">
                            <div class="flex items-center justify-between text-sm">
                                <div class="text-gray-500 dark:text-zinc-400">Progresso</div>
                                <div class="text-gray-900 dark:text-white font-medium">{{ number_format($percentage, 1) }}%</div>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-gray-200 dark:bg-gray-600">
                                <div class="h-2 rounded-full bg-caloriflix-500 transition-all duration-300" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- No water data message -->
            <div class="mt-4 rounded-lg bg-gray-50 p-6 dark:bg-gray-800">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto h-12 w-12 text-gray-400 lucide lucide-glass-water">
                        <path d="M5.116 4.104A1 1 0 0 1 6.11 3h11.78a1 1 0 0 1 .994 1.105L17.19 20.21A2 2 0 0 1 15.2 22H8.8a2 2 0 0 1-2-1.79z"/>
                        <path d="M6 12a5 5 0 0 1 6 0 5 5 0 0 0 6 0"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum registro de água encontrado</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Não há registros de consumo de água para o período selecionado. Comece registrando seu consumo diário de água.
                    </p>
                    <div class="mt-6">
                        <button 
                            wire:click="openWaterModal"
                            class="inline-flex items-center rounded-md bg-caloriflix-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-caloriflix-500 focus:outline-2 focus:outline-offset-2 focus:outline-caloriflix-600"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="-ml-0.5 mr-1.5 h-5 w-5 lucide lucide-plus">
                                <line x1="12" x2="12" y1="5" y2="19"/>
<line x1="5" x2="19" y1="12" y2="12"/>
                            </svg>
                            Registrar Água
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Placeholder for future features -->

    <!-- Empty states for additional reports -->
    <div class="my-8 text-center text-sm text-gray-500 dark:text-gray-400">
        Sem dados encontrados para outros relatórios
    </div>
</div>
</div>
