<div>
    <form wire:submit.prevent="save">
        <div class="space-y-4">
            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Nome
                </label>
                <input
                    type="text"
                    id="name"
                    wire:model="name"
                    class="mt-1 block w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-caloriflix-500 dark:bg-neutral-700 dark:text-neutral-100"
                    placeholder="Digite o nome do usuário"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Email
                </label>
                <input
                    type="email"
                    id="email"
                    wire:model="email"
                    class="mt-1 block w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-caloriflix-500 dark:bg-neutral-700 dark:text-neutral-100"
                    placeholder="Digite o email do usuário"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Senha @if($isEditMode) <span class="text-xs text-neutral-500">(deixe em branco para manter a senha atual)</span> @endif
                </label>
                <input
                    type="password"
                    id="password"
                    wire:model="password"
                    class="mt-1 block w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-caloriflix-500 dark:bg-neutral-700 dark:text-neutral-100"
                    placeholder="Digite a senha"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Confirmation --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                    Confirmar Senha
                </label>
                <input
                    type="password"
                    id="password_confirmation"
                    wire:model="password_confirmation"
                    class="mt-1 block w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-caloriflix-500 dark:bg-neutral-700 dark:text-neutral-100"
                    placeholder="Confirme a senha"
                >
            </div>

            {{-- Roles --}}
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                    Papéis
                </label>
                <div class="space-y-2 max-h-40 overflow-y-auto border border-neutral-300 dark:border-neutral-600 rounded-lg p-3">
                    @foreach($roles as $role)
                        <label class="flex items-center space-x-3 hover:bg-neutral-50 dark:hover:bg-neutral-700 p-2 rounded cursor-pointer">
                            <input
                                type="checkbox"
                                wire:model="selectedRoles"
                                value="{{ $role->name }}"
                                class="form-checkbox h-4 w-4 text-caloriflix-600 rounded focus:ring-caloriflix-500 dark:focus:ring-caloriflix-600 dark:focus:ring-offset-neutral-800"
                            >
                            <span class="text-sm text-neutral-700 dark:text-neutral-300">{{ ucfirst($role->name) }}</span>
                        </label>
                    @endforeach
                </div>
                @error('selectedRoles')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="mt-6 flex justify-end space-x-3">
            <button
                type="button"
                wire:click="$parent.closeModal"
                class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors"
            >
                Cancelar
            </button>
            <button
                type="submit"
                class="px-4 py-2 bg-caloriflix-300 text-black rounded-lg hover:bg-caloriflix-400 transition-colors"
            >
                {{ $isEditMode ? 'Atualizar Usuário' : 'Criar Usuário' }}
            </button>
        </div>
    </form>
</div>