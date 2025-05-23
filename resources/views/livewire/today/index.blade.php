<div class="py-6 max-w-4xl mx-auto">
    <div>
        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                {{ session('message') }}
            </div>
        @endif

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
                    class="px-3 py-1 text-sm font-medium rounded-md bg-green-50 text-green-600 hover:bg-green-100">
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
                        <div class="flex items-center space-x-2">
                            @switch($mealType)
                                @case('cafe_da_manha')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-coffee-icon lucide-coffee"><path d="M10 2v2"/><path d="M14 2v2"/><path d="M16 8a1 1 0 0 1 1 1v8a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V9a1 1 0 0 1 1-1h14a4 4 0 1 1 0 8h-1"/><path d="M6 2v2"/></svg>
                                    @break
                                @case('almoco')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sandwich-icon lucide-sandwich"><path d="m2.37 11.223 8.372-6.777a2 2 0 0 1 2.516 0l8.371 6.777"/><path d="M21 15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-5.25"/><path d="M3 15a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h9"/><path d="m6.67 15 6.13 4.6a2 2 0 0 0 2.8-.4l3.15-4.2"/><rect width="20" height="4" x="2" y="11" rx="1"/></svg>
                                    @break
                                @case('lanche_da_tarde')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-apple-icon lucide-apple"><path d="M12 20.94c1.5 0 2.75 1.06 4 1.06 3 0 6-8 6-12.22A4.91 4.91 0 0 0 17 5c-2.22 0-4 1.44-5 2-1-.56-2.78-2-5-2a4.9 4.9 0 0 0-5 4.78C2 14 5 22 8 22c1.25 0 2.5-1.06 4-1.06Z"/><path d="M10 2c1 .5 2 2 2 5"/></svg>
                                    @break
                                @case('jantar')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-utensils-icon lucide-utensils"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>
                                    @break
                            @endswitch
                            <h3 class="text-lg font-medium">{{ $this->getMealDisplayName($mealType) }}</h3>
                            <span class="text-xs text-green-500 bg-green-100 rounded-full px-2 py-0.5 ml-2">
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
                            <div class="pt-4 text-center text-gray-300">
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
                                            <div class="flex items-center space-x-2">
                                                <button 
                                                    wire:click="deleteMealItem({{ $item['id'] }})"
                                                    wire:confirm="Tem certeza que deseja remover este item?"
                                                    class="bg-red-100 rounded p-2 text-red-500 hover:text-red-700 transition-colors duration-200"
                                                    title="Remover item"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-icon lucide-trash"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                                </button>
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
</div>
