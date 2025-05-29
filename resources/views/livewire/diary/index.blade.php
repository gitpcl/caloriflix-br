<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-4 lg:px-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Diário Pessoal</h2>
                <div>
                    <div class="inline-flex rounded-md">
                        <button 
                            type="button" 
                            wire:click="openCreateModal"
                            class="inline-flex items-center p-2 bg-neutral-100 border border-transparent rounded-md font-semibold text-xs text-neutral-900 uppercase tracking-widest hover:bg-neutral-200 focus:bg-neutral-200 active:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-neutral-200 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Search Bar -->
            <div class="mb-6">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-gray-400 lucide lucide-search">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        class="pl-10 px-2 pr-2 py-2 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-green-300 focus:ring-green-300"
                        placeholder="Pesquisar entradas do diário..."
                    >
                </div>
            </div>
            
            @if($diaries->isEmpty())
                <div class="text-center py-12">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-gray-500 lucide lucide-book">
                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Sem entradas no diário</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Comece a registrar seu progresso diário agora.
                    </p>
                    <div class="mt-6">
                        <button 
                            type="button" 
                            wire:click="openCreateModal"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-300 hover:bg-green-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="-ml-1 mr-2 h-5 w-5 lucide lucide-plus">
                                <line x1="12" x2="12" y1="5" y2="19"/>
                                <line x1="5" x2="19" y1="12" y2="12"/>
                            </svg>
                            Criar primeira entrada
                        </button>
                    </div>
                </div>
            @else
                <!-- Diary Entries List -->
                <div class="space-y-4">
                    @foreach($diaries as $diary)
                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm overflow-hidden">
                            <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($diary->date)->format('d/m/Y') }}
                                        <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($diary->date)->diffForHumans() }}
                                        </span>
                                    </h3>
                                </div>
                                <div class="flex space-x-2">
                                    <button 
                                        wire:click="edit({{ $diary->id }})"
                                        class="text-gray-400 hover:text-green-500 focus:outline-none"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 lucide lucide-pencil">
                                            <path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                            <path d="m15 5 4 4"/>
                                        </svg>
                                    </button>
                                    <button 
                                        wire:click="delete({{ $diary->id }})"
                                        onclick="confirm('Tem certeza que deseja excluir esta entrada?') || event.stopImmediatePropagation()"
                                        class="text-gray-400 hover:text-red-500 focus:outline-none"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 lucide lucide-trash-2">
                                            <path d="M3 6h18"/>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                            <line x1="10" x2="10" y1="11" y2="17"/>
                                            <line x1="14" x2="14" y1="11" y2="17"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Mood Section -->
                                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 text-center">
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Humor do Dia</h4>
                                        <div class="text-3xl">{{ $diary->mood_emoji }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            @if($diary->mood)
                                                Nível {{ $diary->mood }}/5
                                            @else
                                                Não registrado
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Water Section -->
                                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 text-center">
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Consumo de Água</h4>
                                        <div class="text-2xl font-bold text-blue-500 dark:text-blue-400">
                                            {{ $diary->water }} ml
                                        </div>
                                        <div class="mt-2 text-sm">
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min(100, ($diary->water / 2000) * 100) }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ round(($diary->water / 2000) * 100) }}% da meta diária
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Sleep Section -->
                                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 text-center">
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tempo de Sono</h4>
                                        <div class="text-2xl font-bold text-green-300 dark:text-green-400">
                                            {{ $diary->sleep ? $diary->formatted_sleep : 'Não registrado' }}
                                        </div>
                                        @if($diary->sleep)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                                @if($diary->sleep < 420)
                                                    <span class="text-red-500">Abaixo do recomendado</span>
                                                @elseif($diary->sleep >= 420 && $diary->sleep <= 540)
                                                    <span class="text-green-500">Ideal</span>
                                                @else
                                                    <span class="text-blue-500">Acima do recomendado</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Notes Section -->
                                @if($diary->notes)
                                    <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Anotações</h4>
                                        <p class="text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $diary->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $diaries->links() }}
                </div>
            @endif
        </div>
    </div>
    
    <!-- Create/Edit Diary Modal -->
    <div
        x-data="{ 
            isCreating: @entangle('isCreating').live,
            isEditing: @entangle('isEditing').live,
            get show() {
                return this.isCreating || this.isEditing;
            },
            init() {
                console.log('Modal initialized');
                console.log('isCreating:', this.isCreating);
                console.log('isEditing:', this.isEditing);
                console.log('show:', this.show);
                this.$watch('isCreating', value => {
                    console.log('isCreating changed:', value);
                });
                this.$watch('isEditing', value => {
                    console.log('isEditing changed:', value);
                });
                this.$watch('show', value => {
                    console.log('Modal show state:', value);
                });
            }
        }"
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
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
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
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            >
                <form wire:submit.prevent="{{ $isEditing ? 'update' : 'create' }}">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                    {{ $isEditing ? 'Editar Entrada' : 'Nova Entrada no Diário' }}
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data</label>
                                        <input 
                                            type="date" 
                                            wire:model="date" 
                                            id="date" 
                                            class="mt-1 p-2 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-green-300 focus:ring-green-300"
                                        >
                                        @error('date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="mood" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Humor (1-5)</label>
                                        <div class="mt-1 flex justify-between items-center">
                                            <div class="flex space-x-3">
                                                @foreach([1, 2, 3, 4, 5] as $moodValue)
                                                    <button 
                                                        type="button" 
                                                        wire:click="$set('mood', {{ $moodValue }})"
                                                        class="p-2 {{ $mood == $moodValue ? 'bg-green-100 dark:bg-green-900 rounded-full ring-2 ring-green-500' : '' }} focus:outline-none"
                                                    >
                                                        <span class="text-2xl">
                                                            @if($moodValue == 1) 😞
                                                            @elseif($moodValue == 2) 😐
                                                            @elseif($moodValue == 3) 🙂
                                                            @elseif($moodValue == 4) 😊
                                                            @elseif($moodValue == 5) 😁
                                                            @endif
                                                        </span>
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                        @error('mood') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="water" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Consumo de Água (ml)</label>
                                        <input 
                                            type="number" 
                                            wire:model="water" 
                                            id="water" 
                                            min="0"
                                            step="100"
                                            class="mt-1 p-2 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-green-300 focus:ring-green-300"
                                            placeholder="Ex: 2000"
                                        >
                                        @error('water') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="sleep" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tempo de Sono (minutos)</label>
                                        <input 
                                            type="number" 
                                            wire:model="sleep" 
                                            id="sleep" 
                                            min="0"
                                            step="15"
                                            class="mt-1 p-2 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-green-300 focus:ring-green-300"
                                            placeholder="Ex: 480 (8 horas)"
                                        >
                                        @error('sleep') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            Exemplo: 480 (8 horas)
                                        </span>
                                    </div>
                                    
                                    <div>
                                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Anotações</label>
                                        <textarea 
                                            wire:model="notes" 
                                            id="notes" 
                                            rows="4"
                                            class="mt-1 p-2 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-green-300 focus:ring-green-300"
                                            placeholder="Como foi seu dia?"
                                        ></textarea>
                                        @error('notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-300 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            {{ $isEditing ? 'Atualizar' : 'Salvar' }}
                        </button>
                        <button 
                            type="button" 
                            wire:click="closeModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('diary-saved', message => {
                // You can implement toast notifications here if needed
                console.log(message);
            });
            
            // Debug events
            Livewire.on('diary-edit-started', message => {
                console.log('Edit started:', message);
                console.log('isEditing:', @json($isEditing));
                console.log('isCreating:', @json($isCreating));
            });
            
            Livewire.on('diary-error', message => {
                console.error('Diary error:', message);
                alert('Error: ' + message);
            });
        });
        
        // Debug: Log when Alpine.js modal state changes
        document.addEventListener('alpine:init', () => {
            Alpine.data('modalDebug', () => ({
                init() {
                    this.$watch('show', value => {
                        console.log('Modal show state changed:', value);
                    });
                }
            }));
        });
    </script>
</div>
