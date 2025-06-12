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
            
            Livewire.hook('message.processed', (message, component) => {
                console.log('Livewire message processed:', message);
                if (message.response.effects.errors && Object.keys(message.response.effects.errors).length > 0) {
                    console.log('Validation errors:', message.response.effects.errors);
                }
            });
        }
    }" 
    x-cloak
    class="py-6 max-w-4xl mx-auto"
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
                'bg-caloriflix-50 border-caloriflix-500': notificationType === 'info'
            }"
            class="max-w-sm w-full border-l-4 shadow-lg rounded-lg pointer-events-auto"
        >
            <div class="relative p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg 
                            x-show="notificationType === 'success'"
                            class="h-6 w-6 text-caloriflix-400" 
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg 
                            x-show="notificationType === 'error'"
                            class="h-6 w-6 text-red-400" 
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg 
                            x-show="notificationType === 'info'"
                            class="h-6 w-6 text-caloriflix-400" 
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p 
                            :class="{ 
                                'text-caloriflix-800': notificationType === 'success',
                                'text-red-800': notificationType === 'error',
                                'text-caloriflix-800': notificationType === 'info'
                            }"
                            class="text-sm font-medium" 
                            x-text="notificationMessage"
                        ></p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button 
                            @click="showNotification = false" 
                            class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none"
                        >
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div>
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-semibold mb-4">Minhas Metas</h1>
        </div>
        
        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-caloriflix-100 border border-caloriflix-400 text-caloriflix-700 rounded-md">
                {{ session('message') }}
            </div>
        @endif
        
        <div class="space-y-4">
        <!-- Profile Section (Accordion) -->
        <div x-data="{ open: {{ $profileExpanded ? 'true' : 'false' }} }" class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Accordion Header -->
            <div @click="open = !open; $wire.toggleProfile()" class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-600 lucide lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <div>
                        <h3 class="text-lg font-medium">Meu Perfil</h3>
                        <p class="text-sm text-gray-500">Dados pessoais e preferências</p>
                    </div>
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
            <div x-show="open" x-transition class="p-4 border-t border-gray-100">
                <form wire:submit.prevent="saveProfile">
                    <div class="space-y-4">
                        <!-- Error messages -->
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">
                                            Verifique os erros abaixo:
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <ul class="list-disc pl-5 space-y-1">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Weight Field -->
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700">Seu peso</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" step="0.1" id="weight" wire:model="weight" class="block w-full px-3 py-2 pr-16 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-caloriflix-500 focus:border-transparent" placeholder="0.0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                                    <span class="text-gray-500 sm:text-sm">quilogramas (kg)</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Height Field -->
                        <div>
                            <label for="height" class="block text-sm font-medium text-gray-700">Sua altura</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" id="height" wire:model="height" class="block w-full px-3 py-2 pr-16 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-caloriflix-500 focus:border-transparent" placeholder="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                                    <span class="text-gray-500 sm:text-sm">centímetros (cm)</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Gender Field -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Gênero</label>
                            <select id="gender" wire:model="gender" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-caloriflix-500 focus:border-caloriflix-500 sm:text-sm rounded-md">
                                <option value="Masculino">Masculino</option>
                                <option value="Feminino">Feminino</option>
                            </select>
                        </div>
                        
                        <!-- Age Field -->
                        <div>
                            <label for="age" class="block text-sm font-medium text-gray-700">Idade</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" id="age" wire:model="age" class="block w-full px-3 py-2 pr-16 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-caloriflix-500 focus:border-transparent" placeholder="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                                    <span class="text-gray-500 sm:text-sm">anos</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Activity Level Field -->
                        <div>
                            <label for="activityLevel" class="block text-sm font-medium text-gray-700">Nível de Atividade <span class="text-xs bg-caloriflix-100 text-caloriflix-800 px-2 py-1 rounded-full">novo</span></label>
                            <select id="activityLevel" wire:model="activityLevel" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-caloriflix-500 focus:border-caloriflix-500 sm:text-sm rounded-md">
                                <option value="Sedentário">Sedentário</option>
                                <option value="Levemente ativo">Levemente ativo</option>
                                <option value="Moderadamente ativo">Moderadamente ativo</option>
                                <option value="Muito ativo">Muito ativo</option>
                                <option value="Extremamente ativo">Extremamente ativo</option>
                            </select>
                        </div>
                        
                        <!-- Basal Metabolic Rate Field -->
                        <div>
                            <label for="basalMetabolicRate" class="block text-sm font-medium text-gray-700">Taxa metabólica basal</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="number" id="basalMetabolicRate" wire:model="basalMetabolicRate" class="flex-1 block w-full border-gray-300 rounded-l-md sm:text-sm" placeholder="0">
                                <button type="button" wire:click="calculateBMR" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm rounded-r-md hover:bg-gray-100">
                                    Calcular
                                </button>
                            </div>
                        </div>
                        
                        <!-- Use BMR Toggle -->
                        <div class="bg-gray-50 p-4 rounded-md">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label for="useBasalMetabolicRate" class="font-medium text-gray-900">Usar taxa metabólica basal como base</label>
                                    <p class="text-sm text-gray-500">Ativo: Sera usado para calcular déficit/superávit calórico. Desativado: usar sua meta calórica</p>
                                </div>
                                <button 
                                    type="button"
                                    class="relative inline-flex items-center h-6 rounded-full w-11 {{ $useBasalMetabolicRate ? 'bg-caloriflix-600' : 'bg-gray-200' }}"
                                    wire:click="$toggle('useBasalMetabolicRate')"
                                >
                                    <span class="sr-only">Toggle BMR</span>
                                    <span class="translate-x-0 inline-block w-5 h-5 transform bg-white rounded-full transition-transform {{ $useBasalMetabolicRate ? 'translate-x-5' : 'translate-x-1' }}"></span>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-caloriflix-600 text-white text-sm rounded-md hover:bg-caloriflix-700 transition">
                                Salvar perfil
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Goals Section (Accordion) -->
        <div x-data="{ open: {{ $goalsExpanded ? 'true' : 'false' }} }" class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Accordion Header -->
            <div @click="open = !open; $wire.toggleGoals()" class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-600 lucide lucide-target"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                    <div>
                        <h3 class="text-lg font-medium">Minhas Metas</h3>
                        <p class="text-sm text-gray-500">Metas diárias de nutrição</p>
                    </div>
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
            <div x-show="open" x-transition class="p-4 border-t border-gray-100">
                <form wire:submit.prevent="saveGoals">
                    <div class="space-y-4">
                        <!-- Protein Field -->
                        <div>
                            <label for="protein" class="block text-sm font-medium text-gray-700">Proteína</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" id="protein" wire:model="protein" class="block w-full px-3 py-2 pr-12 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-caloriflix-500 focus:border-transparent" placeholder="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                                    <span class="text-gray-500 sm:text-sm">gramas (g)</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Carbs Field -->
                        <div>
                            <label for="carbs" class="block text-sm font-medium text-gray-700">Carboidrato</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" id="carbs" wire:model="carbs" class="block w-full px-3 py-2 pr-12 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-caloriflix-500 focus:border-transparent" placeholder="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                                    <span class="text-gray-500 sm:text-sm">gramas (g)</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Fat Field -->
                        <div>
                            <label for="fat" class="block text-sm font-medium text-gray-700">Gordura</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" id="fat" wire:model="fat" class="block w-full px-3 py-2 pr-12 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-caloriflix-500 focus:border-transparent" placeholder="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                                    <span class="text-gray-500 sm:text-sm">gramas (g)</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Fiber Field -->
                        <div>
                            <label for="fiber" class="block text-sm font-medium text-gray-700">Fibras</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" id="fiber" wire:model="fiber" class="block w-full px-3 py-2 pr-12 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-caloriflix-500 focus:border-transparent" placeholder="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                                    <span class="text-gray-500 sm:text-sm">gramas (g)</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Calories Field -->
                        <div>
                            <label for="calories" class="block text-sm font-medium text-gray-700">Calorias</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" id="calories" wire:model="calories" class="block w-full px-3 py-2 pr-12 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-caloriflix-500 focus:border-transparent" placeholder="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                                    <span class="text-gray-500 sm:text-sm">kcal</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Water Field -->
                        <div>
                            <label for="water" class="block text-sm font-medium text-gray-700">Água</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" id="water" wire:model="water" class="block w-full pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                                    <span class="text-gray-500 sm:text-sm">ml</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Objective Field -->
                        <div>
                            <label for="objective" class="block text-sm font-medium text-gray-700">Objetivo</label>
                            <select id="objective" wire:model="objective" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-caloriflix-500 focus:border-caloriflix-500 sm:text-sm rounded-md">
                                <option value="Perder gordura">Perder gordura</option>
                                <option value="Manter peso">Manter peso</option>
                                <option value="Ganhar massa">Ganhar massa</option>
                            </select>
                        </div>
                        
                        <div class="flex justify-between mt-4">
                            <button type="button" wire:click="suggestGoals" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 text-sm rounded-md hover:bg-gray-200 transition">
                                Sugerir metas <span class="ml-1 text-xs bg-caloriflix-100 text-caloriflix-800 px-2 py-1 rounded-full">novo</span>
                            </button>
                            
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-caloriflix-600 text-white text-sm rounded-md hover:bg-caloriflix-700 transition">
                                Salvar metas
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Diet Plan Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden p-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-600 lucide lucide-utensils"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>
                    <div>
                        <h3 class="font-medium text-gray-900">Seu plano alimentar</h3>
                        <p class="text-sm text-gray-500">Informe seu plano alimentar</p>
                    </div>
                </div>
                <div>
                    <button type="button" wire:click="openDietPlanModal" class="p-2 hover:bg-gray-100 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Training Plan Card -->
        <div class="mb-6 bg-white rounded-lg border border-gray-200 overflow-hidden p-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-600 lucide lucide-dumbbell"><path d="m6.5 6.5 11 11"/><path d="m21 21-1-1"/><path d="m3 3 1 1"/><path d="m18 22 4-4"/><path d="m2 6 4-4"/><path d="m3 10 7-7"/><path d="m14 21 7-7"/></svg>
                    <div>
                        <h3 class="font-medium text-gray-900">Seu plano de treino</h3>
                        <p class="text-sm text-gray-500">Preencha com seu treino da semana</p>
                    </div>
                </div>
                <div>
                    <button type="button" wire:click="openTrainingPlanModal" class="p-2 hover:bg-gray-100 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        </div>
    </div>
    
    <!-- Diet Plan Modal -->
    <div
        x-data="{}"
        x-show="$wire.showDietPlanModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
    >
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- Backdrop -->
            <div 
                x-show="$wire.showDietPlanModal"
                class="fixed inset-0 bg-gray-500 opacity-60"
                @click="$wire.closeModals()"
            ></div>
            
            <!-- Modal content -->
            <div 
                x-show="$wire.showDietPlanModal"
                class="bg-white rounded-lg max-w-xl w-full z-10 relative"
            >
                <!-- Header with close button -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Seu plano alimentar:</h3>
                    <button 
                        type="button" 
                        class="text-gray-400 hover:text-gray-500"
                        @click="$wire.closeModals()"
                    >
                        <span class="sr-only">Fechar</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="saveDietPlan">
                    <div class="px-6 py-4 space-y-4">
                        <!-- Text area for diet plan -->
                        <div>
                            <textarea
                                wire:model="dietPlan"
                                placeholder="Descreva sua dieta da semana aqui..."
                                class="w-full h-40 p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-caloriflix-500 focus:border-transparent"
                            ></textarea>
                        </div>
                        
                        <div class="text-center text-gray-500 my-2">ou</div>
                        
                        <!-- PDF upload area -->
                        <div class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center">
                            <div class="flex justify-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            
                            <div class="mt-2">
                                <label for="dietPlanFile" class="text-caloriflix-600 hover:text-caloriflix-500 cursor-pointer">
                                    Selecione o pdf
                                </label>
                                <input 
                                    id="dietPlanFile"
                                    wire:model="dietPlanFile"
                                    type="file"
                                    accept=".pdf"
                                    class="sr-only"
                                >
                                <p class="text-xs text-gray-500">ou arraste e solte.</p>
                            </div>
                            <p class="text-xs text-gray-500">.PDF até 2MB</p>
                        </div>
                        
                        <p class="text-xs text-gray-500">* Você também pode enviar arquivos PDF através do WhatsApp do Dieta.ai</p>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                        <button
                            type="submit"
                            class="w-full inline-flex justify-center px-4 py-2 bg-caloriflix-600 text-white text-sm rounded-md hover:bg-caloriflix-700 transition"
                        >
                            Salvar plano
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Training Plan Modal -->
    <div
        x-data="{}"
        x-show="$wire.showTrainingPlanModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
    >
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- Backdrop -->
            <div 
                x-show="$wire.showTrainingPlanModal"
                class="fixed inset-0 bg-gray-500 opacity-60"
                @click="$wire.closeModals()"
            ></div>
            
            <!-- Modal content -->
            <div 
                x-show="$wire.showTrainingPlanModal"
                class="bg-white rounded-lg max-w-xl w-full z-10 relative"
            >
                <!-- Header with close button -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Seu plano de treino:</h3>
                    <button 
                        type="button" 
                        class="text-gray-400 hover:text-gray-500"
                        @click="$wire.closeModals()"
                    >
                        <span class="sr-only">Fechar</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="saveTrainingPlan">
                    <div class="px-6 py-4 space-y-4">
                        <!-- Text area for training plan -->
                        <div>
                            <textarea
                                wire:model="trainingPlan"
                                placeholder="Descreva seu plano de treino aqui..."
                                class="w-full h-40 p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-caloriflix-500 focus:border-transparent"
                            ></textarea>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                        <button
                            type="submit"
                            class="w-full inline-flex justify-center px-4 py-2 bg-caloriflix-600 text-white text-sm rounded-md hover:bg-caloriflix-700 transition"
                        >
                            Salvar treino
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>