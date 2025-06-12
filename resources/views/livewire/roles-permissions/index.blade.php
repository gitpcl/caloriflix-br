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
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Roles & Permissions Management</h1>
        <p class="text-gray-600 dark:text-gray-400">Manage system roles and their permissions</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Roles Column --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Roles</h2>

            <div class="space-y-4">
                @foreach($roles as $role)
                    <div class="border dark:border-gray-700 rounded-lg">
                        {{-- Accordion Header --}}
                        <button
                            wire:click="toggleAccordion({{ $role->id }})"
                            class="w-full px-4 py-3 text-left flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                        >
                            <span class="font-medium text-gray-900 dark:text-gray-100">
                                {{ ucfirst($role->name) }}
                                <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                                    ({{ $role->permissions->count() }} permissions)
                                </span>
                            </span>
                            <svg class="w-5 h-5 text-gray-500 transition-transform {{ in_array($role->id, $openAccordions) ? 'rotate-180' : '' }}"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        {{-- Accordion Content --}}
                        @if(in_array($role->id, $openAccordions))
                            <div class="px-4 py-3 border-t dark:border-gray-700">
                                <div class="grid grid-cols-1 gap-2 max-h-96 overflow-y-auto">
                                    @foreach($permissions as $permission)
                                        <label class="flex items-center space-x-3 hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded cursor-pointer">
                                            <input
                                                type="checkbox"
                                                wire:click="togglePermission({{ $role->id }}, {{ $permission->id }})"
                                                {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}
                                                class="form-checkbox h-4 w-4 text-blue-600 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                                            >
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $permission->name }}</span>
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
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Permissions</h2>

            {{-- Create New Permission --}}
            <div class="mb-6">
                <form wire:submit.prevent="createPermission" class="flex gap-2">
                    <input
                        type="text"
                        wire:model="newPermissionName"
                        placeholder="Enter permission name (e.g., users.export)"
                        class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100"
                    >
                    <button
                        type="submit"
                        class="px-4 py-2 bg-caloriflix-300 text-black rounded-lg hover:bg-caloriflix-400 transition-colors"
                    >
                        Add Permission
                    </button>
                </form>
                @error('newPermissionName')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Permissions List --}}
            <div class="space-y-2 max-h-[600px] overflow-y-auto">
                @forelse($permissions as $permission)
                    <div class="flex justify-between items-center p-3 border dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $permission->name }}</span>
                        <button
                            wire:click="deletePermission({{ $permission->id }})"
                            wire:confirm="Are you sure you want to delete this permission?"
                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No permissions created yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>