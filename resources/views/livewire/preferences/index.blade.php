<div 
    class="py-6 max-w-4xl mx-auto"
    x-data="{
        expandedSections: @entangle('expandedSections')
    }"
>
    <h1 class="text-3xl font-bold mb-8 text-center">Preferências</h1>

    <!-- Preferences Section -->
    <div class="mb-4 bg-white rounded-lg overflow-hidden shadow">
        <div class="p-4 flex items-center justify-between cursor-pointer" wire:click="toggleSection('preferences')">
            <div class="flex items-center">
                <div class="bg-gray-100 p-2 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold">Preferências</h2>
                    <p class="text-gray-500 text-sm">Personalize sua experiência</p>
                </div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 transition-transform duration-200" :class="{'transform rotate-180': expandedSections.preferences}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <div 
            x-show="expandedSections.preferences" 
            x-transition:enter="transition ease-out duration-200" 
            x-transition:enter-start="opacity-0 transform -translate-y-2" 
            x-transition:enter-end="opacity-100 transform translate-y-0" 
            x-transition:leave="transition ease-in duration-200" 
            x-transition:leave-start="opacity-100 transform translate-y-0" 
            x-transition:leave-end="opacity-0 transform -translate-y-2" 
            class="px-4 pb-4"
            style="display: none;"
        >
            <div class="bg-gray-50 p-4 rounded-lg space-y-6">
                <!-- Time Zone Setting -->
                <div>
                    <h3 class="text-base font-medium mb-1">Fuso horário</h3>
                    <p class="text-gray-500 text-sm mb-2">Defina de acordo com seu fuso horário para que as refeições sejam exibidas corretamente.</p>
                    <div class="relative">
                        <select 
                            wire:model.live="timeZone" 
                            class="bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded-lg w-full appearance-none focus:outline-none focus:ring-2 focus:ring-green-500"
                        >
                            <option value="UTC-3">UTC-3 São Paulo, Brasil</option>
                            <option value="UTC-5">UTC-5 New York, Estados Unidos</option>
                            <option value="UTC+0">UTC+0 Londres, Reino Unido</option>
                            <option value="UTC+1">UTC+1 Paris, França</option>
                            <option value="UTC+8">UTC+8 Pequim, China</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Silent Mode Toggle -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-medium">Modo silencioso</h3>
                        <p class="text-gray-500 text-sm">Apenas os resultados dos registros serão exibidos. Sugestões automáticas ou mensagens, como 'calculando calorias', não serão mostradas.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="silentModeEnabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>

                <!-- Language Setting -->
                <div>
                    <div class="flex items-center mb-1">
                        <h3 class="text-base font-medium mr-2">Idioma</h3>
                        <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded-full font-medium">beta 🔔</span>
                    </div>
                    <p class="text-gray-500 text-sm mb-2">Atenção: Esta tradução ainda está parcial. Ajude-nos a melhorá-la: contato@dieta.ai</p>
                    <div class="relative">
                        <select 
                            wire:model.live="language" 
                            class="bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded-lg w-full appearance-none focus:outline-none focus:ring-2 focus:ring-green-500"
                        >
                            <option value="Português">Português</option>
                            <option value="English">English</option>
                            <option value="Español">Español</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Prioritize TACO Table Toggle -->
                <div>
                    <div class="flex items-center mb-1">
                        <h3 class="text-base font-medium mr-2">Priorizar tabela TACO</h3>
                        <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded-full font-medium">beta 🔔</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-gray-500 text-sm mr-4">Priorize informações nutricionais da tabela TACO ao invés da base de dados geral.</p>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.live="prioritizeTacoEnabled" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>
                    <div class="mt-2">
                        <a href="#" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                            <span>Leia mais</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Daily Log Toggle -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-medium">Registro diário</h3>
                        <p class="text-gray-500 text-sm">Registre como você se sente em relação à sua alimentação, disposição, humor, etc.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="dailyLogEnabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>

                <!-- Photo with Macros Toggle -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-medium">Foto com macros</h3>
                        <p class="text-gray-500 text-sm">Receba uma foto da sua refeição com os macronutrientes e calorias para compartilhar em seus stories e grupos.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="photoWithMacrosEnabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>

                <!-- Automatic Fasting Toggle -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-medium">Jejum automático</h3>
                        <p class="text-gray-500 text-sm">Identifica automaticamente períodos de jejum intermitente superiores a 12 horas, com base no registro do último alimento.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="autoFastingEnabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>
                
                <!-- Personalize Fasting Link -->
                <div class="mt-2">
                    <a href="#" class="inline-flex items-center text-sm text-blue-600 bg-blue-50 px-3 py-1 rounded-full hover:bg-blue-100">
                        <span>Personalizar</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="ml-1 bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full font-medium">novo✨</span>
                    </a>
                </div>

                <!-- Detailed Foods Toggle -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-medium">Alimentos detalhados</h3>
                        <p class="text-gray-500 text-sm">Exibe informações detalhadas sobre todos os alimentos ao serem adicionados.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="detailedFoodsEnabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>

                <!-- Show Dashboard Toggle -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-medium">Mostrar Dashboard</h3>
                        <p class="text-gray-500 text-sm">Receba o painel diretamente no WhatsApp com seus macronutrientes, etc.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="showDashboardEnabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>

                <!-- Advanced Food Analysis Toggle -->
                <div>
                    <div class="flex items-center flex-wrap mb-1">
                        <h3 class="text-base font-medium mr-2">Análise avançada de alimentos</h3>
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full font-medium mr-1">novo✨</span>
                        <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded-full font-medium">beta 🔔</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-gray-500 text-sm mr-4">Obtenha resultados mais precisos com análise de fotos. Esta opção pode levar mais tempo, mas oferece maior precisão.</p>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.live="advancedFoodAnalysisEnabled" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>
                </div>

                <!-- Group Water Toggle -->
                <div>
                    <div class="flex items-center mb-1">
                        <h3 class="text-base font-medium mr-2">Agrupar água</h3>
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full font-medium">novo✨</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-gray-500 text-sm mr-4">Ao ativar essa funcionalidade a água irá aparecer apenas o somatório total do dia, ao invés de aparecer nas refeições.</p>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.live="groupWaterEnabled" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluations Section -->
    <div class="mb-4 bg-white rounded-lg overflow-hidden shadow">
        <div class="p-4 flex items-center justify-between cursor-pointer" wire:click="toggleSection('evaluations')">
            <div class="flex items-center">
                <div class="bg-amber-100 p-2 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600 lucide lucide-lightbulb"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg>
                </div>
                <div>
                    <div class="flex items-center">
                        <h2 class="text-lg font-semibold mr-2">Avaliações</h2>
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">novo✨</span>
                    </div>
                    <p class="text-gray-500 text-sm">Receba avaliações da IA sobre suas refeições</p>
                </div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 transition-transform duration-200" :class="{'transform rotate-180': expandedSections.evaluations}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <div 
            x-show="expandedSections.evaluations" 
            x-transition:enter="transition ease-out duration-200" 
            x-transition:enter-start="opacity-0 transform -translate-y-2" 
            x-transition:enter-end="opacity-100 transform translate-y-0" 
            x-transition:leave="transition ease-in duration-200" 
            x-transition:leave-start="opacity-100 transform translate-y-0" 
            x-transition:leave-end="opacity-0 transform -translate-y-2" 
            class="px-4 pb-4"
            style="display: none;"
        >
            <div class="bg-gray-50 p-4 rounded-lg space-y-6">
                <!-- Glycemic Index Toggle -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-medium">Índice glicêmico</h3>
                        <p class="text-gray-500 text-sm">Receba informações sobre o índice glicêmico dos alimentos.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="glycemicIndexEnabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>
                
                <!-- Cholesterol Toggle -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-medium">Colesterol</h3>
                        <p class="text-gray-500 text-sm">Obtenha informações sobre o colesterol presente em suas refeições.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="cholesterolEnabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>
                
                <!-- Keto Diet Toggle -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-medium">Dieta cetogênica</h3>
                        <p class="text-gray-500 text-sm">Avalia os alimentos registrados e fornece feedback sobre sua adequação à dieta cetogênica.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="ketoDietEnabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>
                
                <!-- Paleo Diet Toggle -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-medium">Dieta paleolítica</h3>
                        <p class="text-gray-500 text-sm">Avalia os alimentos registrados e fornece feedback sobre sua adequação à dieta paleolítica.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="paleoDietEnabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>
                
                <!-- Low-FODMAP Toggle -->
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center mb-1">
                            <h3 class="text-base font-medium mr-2">Low-FODMAP</h3>
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full font-medium">novo✨</span>
                        </div>
                        <p class="text-gray-500 text-sm">Avalia os alimentos registrados e fornece feedback sobre sua adequação à dieta Low-FODMAP.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="lowFodmapEnabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>
                
                <!-- Low-carb Toggle -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-medium">Low-carb</h3>
                        <p class="text-gray-500 text-sm">Avalia os alimentos registrados e fornece feedback sobre sua adequação à dieta low-carb.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="lowCarbEnabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>
                
                <!-- Meal Plan Evaluation Toggle -->
                {{-- <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center mb-1">
                            <h3 class="text-base font-medium mr-2">Avaliar com plano alimentar</h3>
                            <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded-full font-medium">beta 🔔</span>
                        </div>
                        <p class="text-gray-500 text-sm">Primeiro, adicione seu Plano Alimentar em <a href="#" class="text-indigo-600 hover:underline">Metas do Perfil → Plano Alimentar</a></p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="mealPlanEvaluationEnabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div> --}}
            </div>
        </div>
    </div>

    <!-- Personal Information Section -->
    <div class="mb-4 bg-white rounded-lg overflow-hidden shadow">
        <div class="p-4 flex items-center justify-between cursor-pointer" wire:click="toggleSection('personal_info')">
            <div class="flex items-center">
                <div class="bg-gray-100 p-2 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold">Informações pessoais</h2>
                    <p class="text-gray-500 text-sm">Preencha suas informações pessoais</p>
                </div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 transition-transform duration-200" :class="{'transform rotate-180': expandedSections.personal_info}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <div 
            x-show="expandedSections.personal_info" 
            x-transition:enter="transition ease-out duration-200" 
            x-transition:enter-start="opacity-0 transform -translate-y-2" 
            x-transition:enter-end="opacity-100 transform translate-y-0" 
            x-transition:leave="transition ease-in duration-200" 
            x-transition:leave-start="opacity-100 transform translate-y-0" 
            x-transition:leave-end="opacity-0 transform -translate-y-2" 
            class="px-4 pb-4"
            style="display: none;"
        >
            <div class="bg-gray-50 p-4 rounded-lg">
                <!-- Personal Information Content -->
                <p>Personal information settings will go here</p>
            </div>
        </div>
    </div>

    <!-- Subscription Information Section -->
    <div class="mb-4 bg-white rounded-lg overflow-hidden shadow">
        <div class="p-4 flex items-center justify-between cursor-pointer" wire:click="toggleSection('subscription_info')">
            <div class="flex items-center">
                <div class="bg-gray-100 p-2 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold">Informações da assinatura</h2>
                    <p class="text-gray-500 text-sm">Informações sobre sua assinatura</p>
                </div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 transition-transform duration-200" :class="{'transform rotate-180': expandedSections.subscription_info}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <div 
            x-show="expandedSections.subscription_info" 
            x-transition:enter="transition ease-out duration-200" 
            x-transition:enter-start="opacity-0 transform -translate-y-2" 
            x-transition:enter-end="opacity-100 transform translate-y-0" 
            x-transition:leave="transition ease-in duration-200" 
            x-transition:leave-start="opacity-100 transform translate-y-0" 
            x-transition:leave-end="opacity-0 transform -translate-y-2" 
            class="px-4 pb-4"
            style="display: none;"
        >
            <div class="bg-gray-50 p-4 rounded-lg space-y-6">
                <!-- Subscription Information Content -->
                <div>
                    <h3 class="text-lg font-semibold mb-2">Status da assinatura</h3>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Ativa</span>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-1">Plano</h3>
                    <p class="text-gray-600 mb-3">Plano Trimestral</p>
                    <a href="#" class="inline-block bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-700 font-medium hover:bg-gray-50 transition">
                        Atualizar com 40% de desconto
                    </a>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-1">Método de pagamento</h3>
                    <p class="text-gray-600">Cartão de crédito</p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-1">Último pagamento</h3>
                    <p class="text-gray-600">30/03/2025 R$ 149,70</p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-1">Próximo pagamento</h3>
                    <p class="text-gray-600">28/06/2025</p>
                </div>
                
                <div class="pt-2 space-y-3">
                    <a href="#" class="inline-block w-full md:w-auto bg-white border border-gray-300 rounded-lg px-6 py-2 text-center text-gray-700 font-medium hover:bg-gray-50 transition">
                        Gerenciar assinatura
                    </a>
                    <a href="#" class="inline-block w-full md:w-auto bg-white border border-gray-300 rounded-lg px-6 py-2 text-center text-gray-700 font-medium hover:bg-gray-50 transition">
                        Cancelar renovação
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="mb-4 bg-white rounded-lg overflow-hidden shadow">
        <div class="p-4 flex items-center justify-between cursor-pointer">
            <div class="flex items-center" wire:click="toggleSection('api_integration')">
                <div class="bg-green-100 p-2 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center">
                        <h2 class="text-lg font-semibold mr-2">Integração com API</h2>
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">novo✨</span>
                    </div>
                    <p class="text-gray-500 text-sm">Integre seu app com o dieta.ai.</p>
                </div>
            </div>
            <div class="flex items-center">
                <a href="#" class="text-blue-500 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 transition-transform duration-200" :class="{'transform rotate-180': expandedSections.api_integration}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
        <div 
            x-show="expandedSections.api_integration" 
            x-transition:enter="transition ease-out duration-200" 
            x-transition:enter-start="opacity-0 transform -translate-y-2" 
            x-transition:enter-end="opacity-100 transform translate-y-0" 
            x-transition:leave="transition ease-in duration-200" 
            x-transition:leave-start="opacity-100 transform translate-y-0" 
            x-transition:leave-end="opacity-0 transform -translate-y-2" 
            class="px-4 pb-4"
            style="display: none;"
        >
            <div class="bg-gray-50 p-4 rounded-lg">
                <!-- API Integration Content -->
                <p>API integration settings will go here</p>
            </div>
        </div>
    </div> --}}
</div>
