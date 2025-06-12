<div class="container mx-auto px-4 py-8">
    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">Gestão de Papéis e Permissões</h1>
        <p class="text-neutral-600 dark:text-neutral-400">Gerencie os papéis e permissões do sistema</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Roles Column --}}
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-neutral-900 dark:text-neutral-100">Papéis</h2>

            <div class="space-y-4">
                @foreach($roles as $role)
                    <div class="border dark:border-neutral-700 rounded-lg">
                        {{-- Accordion Header --}}
                        <button
                            wire:click="toggleAccordion({{ $role->id }})"
                            class="w-full px-4 py-3 text-left flex justify-between items-center hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors"
                        >
                            <span class="font-medium text-neutral-900 dark:text-neutral-100">
                                {{ ucfirst($role->name) }}
                                <span class="text-sm text-neutral-500 dark:text-neutral-400 ml-2">
                                    ({{ $role->permissions->count() }} permissões)
                                </span>
                            </span>
                            <svg class="w-5 h-5 text-neutral-500 transition-transform {{ in_array($role->id, $openAccordions) ? 'rotate-180' : '' }}"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        {{-- Accordion Content --}}
                        @if(in_array($role->id, $openAccordions))
                            <div class="px-4 py-3 border-t dark:border-neutral-700">
                                <div class="grid grid-cols-1 gap-2 max-h-96 overflow-y-auto">
                                    @foreach($permissions as $permission)
                                        <label class="flex items-center space-x-3 hover:bg-neutral-50 dark:hover:bg-neutral-700 p-2 rounded cursor-pointer">
                                            <input
                                                type="checkbox"
                                                wire:click="togglePermission({{ $role->id }}, {{ $permission->id }})"
                                                {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}
                                                class="form-checkbox h-4 w-4 text-caloriflix-600 rounded focus:ring-caloriflix-500 dark:focus:ring-caloriflix-600 dark:focus:ring-offset-neutral-800"
                                            >
                                            <span class="text-sm text-neutral-700 dark:text-neutral-300">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Permissions Column --}}
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-neutral-900 dark:text-neutral-100">Permissões</h2>

            {{-- Create New Permission --}}
            <div class="mb-6">
                <form wire:submit.prevent="createPermission" class="flex gap-2">
                    <input
                        type="text"
                        wire:model="newPermissionName"
                        placeholder="Digite o nome da permissão (ex: usuarios.exportar)"
                        class="flex-1 px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-caloriflix-500 dark:bg-neutral-700 dark:text-neutral-100"
                    >
                    <button
                        type="submit"
                        class="px-4 py-2 bg-caloriflix-300 text-black rounded-lg hover:bg-caloriflix-400 transition-colors"
                    >
                        Adicionar Permissão
                    </button>
                </form>
                @error('newPermissionName')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Permissions List --}}
            <div class="space-y-2 max-h-[600px] overflow-y-auto">
                @forelse($permissions as $permission)
                    <div class="flex justify-between items-center p-3 border dark:border-neutral-700 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700">
                        <span class="text-sm text-neutral-700 dark:text-neutral-300">{{ $permission->name }}</span>
                        <button
                            wire:click="deletePermission({{ $permission->id }})"
                            wire:confirm="Tem certeza que deseja excluir esta permissão?"
                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                @empty
                    <p class="text-neutral-500 dark:text-neutral-400 text-center py-4">Nenhuma permissão criada ainda.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>