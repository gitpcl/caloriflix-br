<div class="py-6 max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold mb-4">Hoje</h1>
        
        <div class="flex items-center space-x-2">
            <button 
                wire:click="previousDay" 
                class="p-2 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            
            <button 
                wire:click="today"
                class="px-3 py-1 text-sm font-medium rounded-md bg-indigo-50 text-indigo-600 hover:bg-indigo-100">
                Hoje
            </button>
            
            <button 
                wire:click="nextDay" 
                class="p-2 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            
            <span class="ml-2 text-sm text-gray-500">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>
        </div>
    </div>

    <div class="space-y-4">
        @foreach($mealTypes as $mealType)
            <div x-data="{ open: false }" class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Accordion Header -->
                <div @click="open = !open" class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50">
                    <div class="flex items-center">
                        @switch($mealType)
                            @case('Café da manhã')
                                <img src="{{ asset('img/breakfast.svg') }}" alt="Breakfast" class="w-6 h-6 mr-2">
                                @break
                            @case('Almoço')
                                <img src="{{ asset('img/lunch.svg') }}" alt="Lunch" class="w-6 h-6 mr-2">
                                @break
                            @case('Lanche da tarde')
                                <img src="{{ asset('img/snack.svg') }}" alt="Snack" class="w-6 h-6 mr-2">
                                @break
                            @case('Jantar')
                                <img src="{{ asset('img/dinner.svg') }}" alt="Dinner" class="w-6 h-6 mr-2">
                                @break
                        @endswitch
                        <h3 class="text-lg font-medium">{{ $mealType }}</h3>
                        <span class="text-xs text-gray-500 bg-gray-100 rounded-full px-2 py-0.5 ml-2">
                            {{ count($meals[$mealType]['items']) ?? 0 }}
                        </span>
                    </div>
                    
                    <!-- Chevron icon that rotates -->
                    <svg 
                        class="w-5 h-5 text-gray-500 transition-transform duration-200" 
                        :class="{'rotate-180': open}" 
                        xmlns="http://www.w3.org/2000/svg" 
                        viewBox="0 0 20 20" 
                        fill="currentColor"
                    >
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                
                <!-- Accordion Content -->
                <div x-show="open" x-transition class="px-4 pb-4 border-t border-gray-100">
                    @if($meals[$mealType]['empty'])
                        <div class="py-4 text-center text-gray-500">
                            Vazio
                        </div>
                    @else
                        <ul class="divide-y divide-gray-100">
                            @foreach($meals[$mealType]['items'] as $item)
                                <li class="py-2">
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1">
                                            <p class="font-medium">{{ $item['food']['name'] }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $item['quantity'] }} {{ $item['food']['unit'] }} • 
                                                {{ round($item['food']['protein'] * $item['quantity'], 1) }}p • 
                                                {{ round($item['food']['carbohydrate'] * $item['quantity'], 1) }}c • 
                                                {{ round($item['food']['fat'] * $item['quantity'], 1) }}g • 
                                                {{ round($item['food']['calories'] * $item['quantity'], 1) }} cal
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
