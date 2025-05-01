<div>
    <div class="container max-w-4xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Lembretes e comandos <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">novo</span></h1>
            
            <button 
                wire:click="openCreateModal"
                class="flex items-center justify-center gap-1 px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition"
            >
                <span>+</span> Personalizar lembrete
            </button>
        </div>

        <!-- Video assistance banner -->
        <div class="bg-blue-50 rounded-lg p-4 mb-6 text-center">
            <p class="text-blue-700">
                Assista o nosso vídeo de como funciona a funcionalidade de lembretes →
            </p>
        </div>

        <!-- Fixed Reminders Section -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Fixos</h2>
            
            <div class="space-y-4">
                @if($reminders->count() > 0)
                    @foreach($reminders->where('reminder_type', 'horário específico') as $reminder)
                        <div class="bg-white rounded-lg p-4 border border-gray-200 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <span class="text-gray-600">
                                    @if($reminder->name == 'Café da manhã')
                                        🔍
                                    @elseif($reminder->name == 'Almoço')
                                        🍽️
                                    @elseif($reminder->name == 'Lanche da tarde')
                                        🥪
                                    @elseif($reminder->name == 'Jantar')
                                        🐟
                                    @else
                                        ⏰
                                    @endif
                                </span>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $reminder->name }}</h3>
                                    <p class="text-sm text-gray-500">
                                        @if($reminder->reminder_type == 'horário específico')
                                            Todos os dias às {{ substr($reminder->start_time, 0, 5) }} {{ (int)substr($reminder->start_time, 0, 2) >= 12 ? 'PM' : 'AM' }}
                                        @else
                                            A cada {{ $reminder->interval_hours }}h {{ $reminder->interval_minutes > 0 ? $reminder->interval_minutes . 'm' : '' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div>
                                <button 
                                    type="button"
                                    class="relative inline-flex items-center h-6 rounded-full w-11 {{ $reminder->active ? 'bg-blue-600' : 'bg-gray-200' }}"
                                    wire:click="toggleActive({{ $reminder->id }})"
                                >
                                    <span class="sr-only">Toggle active</span>
                                    <span class="{{ $reminder->active ? 'translate-x-6' : 'translate-x-1' }} inline-block w-4 h-4 transform bg-white rounded-full transition"></span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>Você ainda não tem lembretes fixos.</p>
                    </div>
                @endif
            </div>

            <!-- Customize button -->
            {{-- <div class="mt-4 flex justify-center">
                <button 
                    wire:click="openCreateModal"
                    class="flex items-center justify-center gap-1 px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition"
                >
                    <span>+</span> Personalizar lembrete
                </button>
            </div> --}}
        </div>

        <!-- Personalizados Section -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Personalizados</h2>
            
            <div class="space-y-4">
                @if($reminders->where('reminder_type', 'intervalo de tempo')->count() > 0)
                    @foreach($reminders->where('reminder_type', 'intervalo de tempo') as $reminder)
                        <div class="bg-white rounded-lg p-4 border border-gray-200 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <span class="text-gray-600">
                                    @if($reminder->name == 'Beber Água')
                                        💧
                                    @elseif($reminder->name == 'Tomar Remédio')
                                        💊
                                    @elseif($reminder->name == 'Tomar Suplemento')
                                        🧪
                                    @elseif($reminder->name == 'Se Movimentar')
                                        🚶
                                    @else
                                        ⏰
                                    @endif
                                </span>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $reminder->name }}</h3>
                                    <p class="text-sm text-gray-500">
                                        A cada {{ $reminder->interval_hours }}h {{ $reminder->interval_minutes > 0 ? $reminder->interval_minutes . 'm' : '' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button 
                                    type="button"
                                    class="relative inline-flex items-center h-6 rounded-full w-11 {{ $reminder->active ? 'bg-blue-600' : 'bg-gray-200' }}"
                                    wire:click="toggleActive({{ $reminder->id }})"
                                >
                                    <span class="sr-only">Toggle active</span>
                                    <span class="{{ $reminder->active ? 'translate-x-6' : 'translate-x-1' }} inline-block w-4 h-4 transform bg-white rounded-full transition"></span>
                                </button>
                                <div class="relative" x-data="{ open: false }">
                                    <!-- Three dots button -->
                                    <button 
                                        type="button" 
                                        @click="open = !open" 
                                        class="ml-2 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                    
                                    <!-- Dropdown menu -->
                                    <div 
                                        x-show="open" 
                                        @click.outside="open = false"
                                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        style="display: none;"
                                    >
                                        <div class="py-1 rounded-md bg-white shadow-xs">
                                            <button 
                                                wire:click="duplicateReminder({{ $reminder->id }})"
                                                @click="open = false"
                                                class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                                Duplicar
                                            </button>
                                            
                                            <button 
                                                wire:click="deleteReminder({{ $reminder->id }})" 
                                                @click="open = false"
                                                class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                                                onclick="if(!confirm('Tem certeza que deseja excluir este lembrete? Esta ação não pode ser desfeita.')) return false;"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Excluir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>Você ainda não tem lembretes personalizados.</p>
                    </div>
                @endif
            </div>

            <!-- Customize button -->
            {{-- <div class="mt-4 flex justify-center">
                <button 
                    wire:click="openCreateModal"
                    class="flex items-center justify-center gap-1 px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition"
                >
                    <span>+</span> Personalizar lembrete
                </button>
            </div> --}}
        </div>

        <!-- Suggestions Section -->
        <div>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Sugestões <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">novo</span></h2>
            
            <div class="space-y-4">
                <!-- Beber água -->
                @if(!$reminders->where('reminder_type', 'intervalo de tempo')->where('name', 'Beber Água')->count())
                <div class="bg-white rounded-lg p-4 border border-gray-200 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <span class="text-gray-600">💧</span>
                        <div>
                            <h3 class="font-medium text-gray-900">Beber água</h3>
                            <p class="text-sm text-gray-500">A cada 1 hora</p>
                        </div>
                    </div>
                    <div>
                        <button 
                            wire:click="prepareSuggestion('Beber Água', 'Lembrar de beber água a cada 1 hora', 1, 0)"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <span>+</span>
                        </button>
                    </div>
                </div>
                @endif

                <!-- Tomar remédio -->
                @if(!$reminders->where('reminder_type', 'intervalo de tempo')->where('name', 'Tomar Remédio')->count())
                <div class="bg-white rounded-lg p-4 border border-gray-200 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <span class="text-gray-600">💊</span>
                        <div>
                            <h3 class="font-medium text-gray-900">Tomar remédio</h3>
                            <p class="text-sm text-gray-500">A cada 8 horas</p>
                        </div>
                    </div>
                    <div>
                        <button 
                            wire:click="prepareSuggestion('Tomar Remédio', 'Lembrar de tomar remédio a cada 8 horas', 8, 0)"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <span>+</span>
                        </button>
                    </div>
                </div>
                @endif

                <!-- Tomar suplemento -->
                @if(!$reminders->where('reminder_type', 'intervalo de tempo')->where('name', 'Tomar Suplemento')->count())
                <div class="bg-white rounded-lg p-4 border border-gray-200 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <span class="text-gray-600">🧪</span>
                        <div>
                            <h3 class="font-medium text-gray-900">Tomar suplemento</h3>
                            <p class="text-sm text-gray-500">Todos os dias às 8h AM</p>
                        </div>
                    </div>
                    <div>
                        <button 
                            wire:click="prepareSuggestion('Tomar Suplemento', 'Lembrar de tomar suplemento todos os dias às 8h', 24, 0)"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <span>+</span>
                        </button>
                    </div>
                </div>
                @endif

                <!-- Se movimentar -->
                @if(!$reminders->where('reminder_type', 'intervalo de tempo')->where('name', 'Se Movimentar')->count())
                <div class="bg-white rounded-lg p-4 border border-gray-200 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <span class="text-gray-600">🚶</span>
                        <div>
                            <h3 class="font-medium text-gray-900">Se movimentar</h3>
                            <p class="text-sm text-gray-500">A cada 3 horas</p>
                        </div>
                    </div>
                    <div>
                        <button 
                            wire:click="prepareSuggestion('Se Movimentar', 'Lembrar de se movimentar a cada 3 horas', 3, 0)"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <span>+</span>
                        </button>
                    </div>
                </div>
                @endif
                
                <!-- No suggestions message -->
                @if(
                    $reminders->where('reminder_type', 'intervalo de tempo')->where('name', 'Beber Água')->count() &&
                    $reminders->where('reminder_type', 'intervalo de tempo')->where('name', 'Tomar Remédio')->count() &&
                    $reminders->where('reminder_type', 'intervalo de tempo')->where('name', 'Tomar Suplemento')->count() &&
                    $reminders->where('reminder_type', 'intervalo de tempo')->where('name', 'Se Movimentar')->count()
                )
                <div class="text-center py-8 text-gray-500">
                    <p>Você já adicionou todas as sugestões disponíveis.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal for creating/editing reminders -->
    <div
        x-data="{}"
        x-show="$wire.showModal"
        x-on:keydown.escape.window="$wire.closeModal()"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- Backdrop -->
            <div 
                x-show="$wire.showModal"
                class="fixed inset-0 bg-gray-500 opacity-60"
                @click="$wire.closeModal()"
            ></div>

            <!-- Modal content -->
            <div
                x-show="$wire.showModal"
                class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full"
            >
                <!-- Header with toggle -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center space-x-2">
                        <span class="text-red-500">⏰</span>
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $isEditing ? 'Editar Lembrete' : 'Novo Lembrete ' . ($reminderId ? $reminderId : '') }}
                        </h3>
                    </div>
                    <div>
                        <button 
                            type="button"
                            wire:click="$set('reminderEnabled', {{ !$reminderEnabled ? 'true' : 'false' }})"
                            class="relative inline-flex items-center h-6 rounded-full w-11 {{ $reminderEnabled ? 'bg-blue-600' : 'bg-gray-200' }}"
                        >
                            <span class="sr-only">Toggle active</span>
                            <span class="{{ $reminderEnabled ? 'translate-x-6' : 'translate-x-1' }} inline-block w-4 h-4 transform bg-white rounded-full transition"></span>
                        </button>
                    </div>
                </div>

                <form wire:submit.prevent="saveReminder">
                    <div class="px-6 py-4 space-y-4">
                        <!-- Name field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                            <input 
                                type="text" 
                                id="name" 
                                wire:model="name" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Nome do lembrete"
                            >
                            @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description field -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                            <textarea 
                                id="description" 
                                wire:model="description" 
                                rows="3" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Descrição do lembrete"
                            ></textarea>
                        </div>

                        <!-- Reminder Type -->
                        <div>
                            <label for="reminderType" class="block text-sm font-medium text-gray-700">Tipo do lembrete</label>
                            <select 
                                id="reminderType" 
                                wire:model="reminderType"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                            >
                                <option value="intervalo de tempo">Intervalo de tempo</option>
                                <option value="horário específico">Horário específico</option>
                            </select>
                        </div>

                        <!-- Interval -->
                        <div x-show="$wire.reminderType === 'intervalo de tempo'">
                            <label class="block text-sm font-medium text-gray-700">Intervalo</label>
                            <div class="mt-1 grid grid-cols-2 gap-3">
                                <div class="flex items-center">
                                    <input 
                                        type="number" 
                                        wire:model="intervalHours"
                                        min="0" 
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    >
                                    <span class="ml-2 text-gray-500">horas</span>
                                </div>
                                <div class="flex items-center">
                                    <input 
                                        type="number" 
                                        wire:model="intervalMinutes"
                                        min="0" 
                                        max="59" 
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    >
                                    <span class="ml-2 text-gray-500">minutos</span>
                                </div>
                            </div>
                            @error('intervalHours') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            @error('intervalMinutes') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Time Window -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Janela de Tempo</label>
                            <div class="grid grid-cols-2 gap-4 mt-1">
                                <div>
                                    <label for="startTime" class="block text-sm text-gray-500">Início</label>
                                    <input 
                                        type="time" 
                                        id="startTime" 
                                        wire:model="startTime" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    >
                                </div>
                                <div>
                                    <label for="endTime" class="block text-sm text-gray-500">Fim</label>
                                    <input 
                                        type="time" 
                                        id="endTime" 
                                        wire:model="endTime" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Buttons toggle -->
                        <div class="flex items-center">
                            <button 
                                type="button"
                                wire:click="$set('buttonsEnabled', {{ !$buttonsEnabled ? 'true' : 'false' }})"
                                class="relative inline-flex items-center h-6 rounded-full w-11 {{ $buttonsEnabled ? 'bg-blue-600' : 'bg-gray-200' }}"
                            >
                                <span class="sr-only">Toggle buttons</span>
                                <span class="{{ $buttonsEnabled ? 'translate-x-6' : 'translate-x-1' }} inline-block w-4 h-4 transform bg-white rounded-full transition"></span>
                            </button>
                            <span class="ml-2 text-sm text-gray-700">Habilitar botões</span>
                        </div>

                        <!-- Auto command toggle -->
                        <div class="flex items-center">
                            <button 
                                type="button"
                                wire:click="$set('autoCommandEnabled', {{ !$autoCommandEnabled ? 'true' : 'false' }})"
                                class="relative inline-flex items-center h-6 rounded-full w-11 {{ $autoCommandEnabled ? 'bg-blue-600' : 'bg-gray-200' }}"
                            >
                                <span class="sr-only">Toggle auto command</span>
                                <span class="{{ $autoCommandEnabled ? 'translate-x-6' : 'translate-x-1' }} inline-block w-4 h-4 transform bg-white rounded-full transition"></span>
                            </button>
                            <span class="ml-2 text-sm text-gray-700">Executar comando automaticamente <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded">novo</span></span>
                        </div>

                    </div>

                    <!-- Footer actions -->
                    <div class="flex justify-between px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex space-x-2">
                            <button 
                                type="button"
                                wire:click="testReminder"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                Testar
                            </button>
                            <button 
                                type="button"
                                @click="$wire.closeModal()"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <button 
                            type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            {{ $isEditing ? 'Salvar alterações' : 'Criar lembrete' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>