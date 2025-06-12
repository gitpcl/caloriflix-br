<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-neutral-900 pb-20 lg:pb-0">
        <flux:sidebar sticky stashable class="hidden lg:flex border-r border-neutral-800 bg-neutral-900 text-white">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('today.index') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group class="grid space-y-1">
                    <!-- Hoje Item -->
                    <a href="{{ route('today.index') }}"
                       class="flex items-center justify-between rounded-[10px] px-3 py-2 text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('today.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                       wire:navigate>
                        <div class="flex items-center">
                            <span class="mr-2 {{ request()->routeIs('today.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-icon lucide-house"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                            </span>
                            <span class="{{ request()->routeIs('today.index') ? 'text-caloriflix-300' : '' }}">{{ __('Hoje') }}</span>
                        </div>
                    </a>

                    <!-- Alpine.js Accordion for Relatórios -->
                    <div x-data="{ open: {{ request()->routeIs('diary.index') || request()->routeIs('measurements.index') || request()->routeIs('reports.index') ? 'true' : 'false' }} }" class="relative">
                        <!-- Accordion Header -->
                        <button
                            @click="open = !open"
                            class="flex w-full items-center justify-between rounded-[10px] px-3 py-2 text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all group"
                        >
                            <div class="flex items-center">
                                <span class="mr-2 {{ request()->routeIs('diary.index') || request()->routeIs('measurements.index') || request()->routeIs('reports.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-bar-big-icon lucide-chart-bar-big"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><rect x="7" y="13" width="9" height="4" rx="1"/><rect x="7" y="5" width="12" height="4" rx="1"/></svg>
                                </span>
                                <span class="{{ request()->routeIs('diary.index') || request()->routeIs('measurements.index') || request()->routeIs('reports.index') ? 'text-caloriflix-300' : '' }}">{{ __('Relatórios') }}</span>
                            </div>
                            <svg
                                fill="none"
                                height="16"
                                viewBox="0 0 16 16"
                                width="16"
                                xmlns="http://www.w3.org/2000/svg"
                                class="transition-transform duration-200"
                                :class="open ? 'rotate-180' : ''"
                            >
                                <path d="M4 7L8 11L12 7" stroke="#C4C4CC" stroke-linecap="square" stroke-linejoin="round" stroke-width="1.5"></path>
                            </svg>
                        </button>

                        <!-- Accordion Content -->
                        <div
                            x-show="open"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="ml-6 mt-1 space-y-1"
                        >
                            <!-- Relatórios Item -->
                            <a
                                href="{{ route('reports.index') }}"
                                class="flex items-center rounded-[10px] px-3 py-2 text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('reports.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                                wire:navigate
                            >
                                <span class="mr-2 {{ request()->routeIs('reports.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame-icon lucide-flame"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
                                </span>
                                <span class="{{ request()->routeIs('reports.index') ? 'text-caloriflix-300' : '' }}">{{ __('Relatórios') }}</span>
                            </a>

                            <!-- Diário Item -->
                            <a
                                href="{{ route('diary.index') }}"
                                class="flex items-center rounded-[10px] px-3 py-2 text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('diary.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                                wire:navigate
                            >
                                <span class="mr-2 {{ request()->routeIs('diary.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open-icon lucide-book-open"><path d="M12 7v14"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/></svg>
                                </span>
                                <span class="{{ request()->routeIs('diary.index') ? 'text-caloriflix-300' : '' }}">{{ __('Diário') }}</span>
                            </a>

                            <!-- Medidas Item -->
                            <a
                                href="{{ route('measurements.index') }}"
                                class="flex items-center rounded-[10px] px-3 py-2 text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('measurements.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                                wire:navigate
                            >
                                <span class="mr-2 {{ request()->routeIs('measurements.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-view-icon lucide-view"><path d="M21 17v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2"/><path d="M21 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2"/><circle cx="12" cy="12" r="1"/><path d="M18.944 12.33a1 1 0 0 0 0-.66 7.5 7.5 0 0 0-13.888 0 1 1 0 0 0 0 .66 7.5 7.5 0 0 0 13.888 0"/></svg>
                                </span>
                                <span class="{{ request()->routeIs('measurements.index') ? 'text-caloriflix-300' : '' }}">{{ __('Medidas') }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- Meus Alimentos Item -->
                    <a href="{{ route('foods.index') }}"
                       class="flex items-center justify-between rounded-[10px] px-3 py-2 text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('foods.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                       wire:navigate>
                        <div class="flex items-center">
                            <span class="mr-2 {{ request()->routeIs('foods.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star-icon lucide-star"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/></svg>
                            </span>
                            <span class="{{ request()->routeIs('foods.index') ? 'text-caloriflix-300' : '' }}">{{ __('Meus Alimentos') }}</span>
                        </div>
                    </a>

                    <!-- Minhas Receitas Item -->
                    <a href="{{ route('recipes.index') }}"
                       class="flex items-center justify-between rounded-[10px] px-3 py-2 text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('recipes.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                       wire:navigate>
                        <div class="flex items-center">
                            <span class="mr-2 {{ request()->routeIs('recipes.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-cooking-pot-icon lucide-cooking-pot"><path d="M2 12h20"/><path d="M20 12v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8"/><path d="m4 8 16-4"/><path d="m8.86 6.78-.45-1.81a2 2 0 0 1 1.45-2.43l1.94-.48a2 2 0 0 1 2.43 1.46l.45 1.8"/></svg>
                            </span>
                            <span class="{{ request()->routeIs('recipes.index') ? 'text-caloriflix-300' : '' }}">{{ __('Minhas Receitas') }}</span>
                        </div>
                    </a>

                    <!-- Metas e Perfil Item -->
                    <a href="{{ route('goals.index') }}"
                       class="flex items-center justify-between rounded-[10px] px-3 py-2 text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('goals.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                       wire:navigate>
                        <div class="flex items-center">
                            <span class="mr-2 {{ request()->routeIs('goals.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-menu-icon lucide-square-menu"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 8h10"/><path d="M7 12h10"/><path d="M7 16h10"/></svg>
                            </span>
                            <span class="{{ request()->routeIs('goals.index') ? 'text-caloriflix-300' : '' }}">{{ __('Metas e Perfil') }}</span>
                        </div>
                    </a>

                    <!-- Lembretes Item -->
                    <a href="{{ route('reminders.index') }}"
                       class="flex items-center justify-between rounded-[10px] px-3 py-2 text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('reminders.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                       wire:navigate>
                        <div class="flex items-center">
                            <span class="mr-2 {{ request()->routeIs('reminders.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-timer-icon lucide-timer"><line x1="10" x2="14" y1="2" y2="2"/><line x1="12" x2="15" y1="14" y2="11"/><circle cx="12" cy="14" r="8"/></svg>
                            </span>
                            <span class="{{ request()->routeIs('reminders.index') ? 'text-caloriflix-300' : '' }}">{{ __('Lembretes') }}</span>
                        </div>
                    </a>

                    <!-- Preferências Item -->
                    <a href="{{ route('preferences.index') }}"
                       class="flex items-center justify-between rounded-[10px] px-3 py-2 text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('preferences.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                       wire:navigate>
                        <div class="flex items-center">
                            <span class="mr-2 {{ request()->routeIs('preferences.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-icon lucide-settings"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                            </span>
                            <span class="{{ request()->routeIs('preferences.index') ? 'text-caloriflix-300' : '' }}">{{ __('Preferências') }}</span>
                        </div>
                    </a>

                    <!-- SuperAdmin Section -->
                    @if(auth()->user()->hasRole('superadmin'))
                        <div class="mt-4 mb-2 px-3">
                            <h3 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Admin</h3>
                        </div>

                        <!-- Users Management Item -->
                        <a href="{{ route('users.index') }}"
                           class="flex items-center justify-between rounded-[10px] px-3 py-2 text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('users.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                           wire:navigate>
                            <div class="flex items-center">
                                <span class="mr-2 {{ request()->routeIs('users.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                </span>
                                <span class="{{ request()->routeIs('users.index') ? 'text-caloriflix-300' : '' }}">{{ __('Usuários') }}</span>
                            </div>
                        </a>

                        <!-- Roles & Permissions Item -->
                        <a href="{{ route('roles-permissions.index') }}"
                           class="flex items-center justify-between rounded-[10px] px-3 py-2 text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('roles-permissions.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                           wire:navigate>
                            <div class="flex items-center">
                                <span class="mr-2 {{ request()->routeIs('roles-permissions.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                                </span>
                                <span class="{{ request()->routeIs('roles-permissions.index') ? 'text-caloriflix-300' : '' }}">{{ __('Papéis e Permissões') }}</span>
                            </div>
                        </a>
                    @endif
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            {{-- <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist> --}}

            <!-- Desktop User Menu -->
            <div class="relative" x-data="{ open: false }">
                <button
                    @click="open = !open"
                    class="flex w-full items-center justify-between rounded-[10px] px-3 py-2 text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all"
                >
                    <div class="flex items-center">
                        <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg mr-2">
                            <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-700 text-white text-sm font-medium">
                                {{ auth()->user()->initials() }}
                            </span>
                        </span>
                        <div class="flex flex-col text-left">
                            <span class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</span>
                            <span class="text-xs text-neutral-400 truncate">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                    <svg
                        fill="none"
                        height="16"
                        viewBox="0 0 16 16"
                        width="16"
                        xmlns="http://www.w3.org/2000/svg"
                        class="transition-transform duration-200 text-neutral-400"
                        :class="open ? 'rotate-180' : ''"
                    >
                        <path d="M4 7L8 11L12 7" stroke="currentColor" stroke-linecap="square" stroke-linejoin="round" stroke-width="1.5"></path>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute bottom-full left-0 w-full mb-2 bg-neutral-900 border border-neutral-800 rounded-[10px] shadow-lg z-50"
                    style="display: none;"
                >
                    <div class="py-2">
                        <!-- Settings -->
                        <a
                            href="{{ route('settings.profile') }}"
                            class="flex items-center rounded-[10px] mx-2 px-3 py-2 text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all"
                            wire:navigate
                        >
                            <span class="mr-2 text-neutral-200">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </span>
                            {{ __('Settings') }}
                        </a>

                        <!-- Separator -->
                        <div class="mx-2 my-1 border-t border-neutral-800"></div>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button
                                type="submit"
                                class="flex items-center rounded-[10px] mx-2 px-3 py-2 text-sm font-medium text-neutral-300 hover:bg-red-900/20 hover:text-red-400 transition-all w-full text-left"
                            >
                                <span class="mr-2 text-neutral-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                        <polyline points="16,17 21,12 16,7"/>
                                        <line x1="21" x2="9" y1="12" y2="12"/>
                                    </svg>
                                </span>
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </flux:sidebar>

        <!-- iOS-style Bottom Navigation (Mobile Only) -->
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-neutral-900 border-t border-neutral-800 z-50">
            <div class="flex overflow-x-auto scrollbar-hide py-2 px-4 space-x-1 bottom-nav-scroll" style="scroll-behavior: auto;">
                <!-- Home/Hoje -->
                <a href="{{ route('today.index') }}"
                   class="flex flex-col items-center justify-center rounded-[10px] px-3 py-2 min-w-[70px] text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('today.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                   wire:navigate>
                    <span class="mb-1 {{ request()->routeIs('today.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9,22 9,12 15,12 15,22"/>
                        </svg>
                    </span>
                    <span class="text-xs {{ request()->routeIs('today.index') ? 'text-caloriflix-300' : '' }}">
                        {{ __('Hoje') }}
                    </span>
                </a>

                <!-- Reports/Relatórios -->
                <a href="{{ route('reports.index') }}"
                   class="flex flex-col items-center justify-center rounded-[10px] px-3 py-2 min-w-[70px] text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('reports.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                   wire:navigate>
                    <span class="mb-1 {{ request()->routeIs('reports.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>
                        </svg>
                    </span>
                    <span class="text-xs {{ request()->routeIs('reports.index') ? 'text-caloriflix-300' : '' }}">
                        {{ __('Relatórios') }}
                    </span>
                </a>

                <!-- Diary/Diário -->
                <a href="{{ route('diary.index') }}"
                   class="flex flex-col items-center justify-center rounded-[10px] px-3 py-2 min-w-[70px] text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('diary.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                   wire:navigate>
                    <span class="mb-1 {{ request()->routeIs('diary.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 7v14"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/>
                        </svg>
                    </span>
                    <span class="text-xs {{ request()->routeIs('diary.index') ? 'text-caloriflix-300' : '' }}">
                        {{ __('Diário') }}
                    </span>
                </a>

                <!-- Measurements/Medidas -->
                <a href="{{ route('measurements.index') }}"
                   class="flex flex-col items-center justify-center rounded-[10px] px-3 py-2 min-w-[70px] text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('measurements.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                   wire:navigate>
                    <span class="mb-1 {{ request()->routeIs('measurements.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 17v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2"/><path d="M21 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2"/><circle cx="12" cy="12" r="1"/><path d="M18.944 12.33a1 1 0 0 0 0-.66 7.5 7.5 0 0 0-13.888 0 1 1 0 0 0 0 .66 7.5 7.5 0 0 0 13.888 0"/>
                        </svg>
                    </span>
                    <span class="text-xs {{ request()->routeIs('measurements.index') ? 'text-caloriflix-300' : '' }}">
                        {{ __('Medidas') }}
                    </span>
                </a>

                <!-- Foods/Alimentos -->
                <a href="{{ route('foods.index') }}"
                   class="flex flex-col items-center justify-center rounded-[10px] px-3 py-2 min-w-[70px] text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('foods.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                   wire:navigate>
                    <span class="mb-1 {{ request()->routeIs('foods.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/>
                        </svg>
                    </span>
                    <span class="text-xs {{ request()->routeIs('foods.index') ? 'text-caloriflix-300' : '' }}">
                        {{ __('Alimentos') }}
                    </span>
                </a>

                <!-- Recipes/Receitas -->
                <a href="{{ route('recipes.index') }}"
                   class="flex flex-col items-center justify-center rounded-[10px] px-3 py-2 min-w-[70px] text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('recipes.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                   wire:navigate>
                    <span class="mb-1 {{ request()->routeIs('recipes.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12h20"/><path d="M20 12v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8"/><path d="m4 8 16-4"/><path d="m8.86 6.78-.45-1.81a2 2 0 0 1 1.45-2.43l1.94-.48a2 2 0 0 1 2.43 1.46l.45 1.8"/>
                        </svg>
                    </span>
                    <span class="text-xs {{ request()->routeIs('recipes.index') ? 'text-caloriflix-300' : '' }}">
                        {{ __('Receitas') }}
                    </span>
                </a>

                <!-- Goals/Metas -->
                <a href="{{ route('goals.index') }}"
                   class="flex flex-col items-center justify-center rounded-[10px] px-3 py-2 min-w-[70px] text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('goals.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                   wire:navigate>
                    <span class="mb-1 {{ request()->routeIs('goals.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 8h10"/><path d="M7 12h10"/><path d="M7 16h10"/>
                        </svg>
                    </span>
                    <span class="text-xs {{ request()->routeIs('goals.index') ? 'text-caloriflix-300' : '' }}">
                        {{ __('Metas') }}
                    </span>
                </a>

                <!-- Reminders/Lembretes -->
                <a href="{{ route('reminders.index') }}"
                   class="flex flex-col items-center justify-center rounded-[10px] px-3 py-2 min-w-[70px] text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('reminders.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                   wire:navigate>
                    <span class="mb-1 {{ request()->routeIs('reminders.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="10" x2="14" y1="2" y2="2"/><line x1="12" x2="15" y1="14" y2="11"/><circle cx="12" cy="14" r="8"/>
                        </svg>
                    </span>
                    <span class="text-xs {{ request()->routeIs('reminders.index') ? 'text-caloriflix-300' : '' }}">
                        {{ __('Lembretes') }}
                    </span>
                </a>

                <!-- Preferences/Preferências -->
                <a href="{{ route('preferences.index') }}"
                   class="flex flex-col items-center justify-center rounded-[10px] px-3 py-2 min-w-[70px] text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-white transition-all {{ request()->routeIs('preferences.index') ? 'bg-neutral-800 text-caloriflix-300' : '' }}"
                   wire:navigate>
                    <span class="mb-1 {{ request()->routeIs('preferences.index') ? 'text-caloriflix-300' : 'text-neutral-200' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </span>
                    <span class="text-xs {{ request()->routeIs('preferences.index') ? 'text-caloriflix-300' : '' }}">
                        {{ __('Preferências') }}
                    </span>
                </a>

                <!-- Logout/Sair -->
                <form method="POST" action="{{ route('logout') }}" class="contents">
                    @csrf
                    <button type="submit" class="flex flex-col items-center justify-center rounded-[10px] px-3 py-2 min-w-[70px] text-sm font-medium text-neutral-300 hover:bg-neutral-800 hover:text-red-400 transition-all">
                        <span class="mb-1 text-neutral-200 group-hover:text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" x2="9" y1="12" y2="12"/>
                            </svg>
                        </span>
                        <span class="text-xs hover:text-red-400">
                            {{ __('Sair') }}
                        </span>
                    </button>
                </form>
            </div>
        </nav>

        {{ $slot }}

        @fluxScripts

        <script>
            // Preserve bottom navigation scroll position - Immediate version
            let bottomNavScrollPosition = parseInt(sessionStorage.getItem('bottomNavScrollLeft')) || 0;

            function saveScrollPosition() {
                const bottomNav = document.querySelector('.bottom-nav-scroll');
                if (bottomNav) {
                    bottomNavScrollPosition = bottomNav.scrollLeft;
                    sessionStorage.setItem('bottomNavScrollLeft', bottomNavScrollPosition);
                }
            }

            function restoreScrollPosition() {
                const bottomNav = document.querySelector('.bottom-nav-scroll');
                if (bottomNav && bottomNavScrollPosition > 0) {
                    bottomNav.scrollLeft = bottomNavScrollPosition;
                }
            }

            // Immediately restore on script load
            document.addEventListener('DOMContentLoaded', function() {
                restoreScrollPosition();
            });

            // Also try immediately when the element is available
            function tryRestoreImmediate() {
                const bottomNav = document.querySelector('.bottom-nav-scroll');
                if (bottomNav) {
                    if (bottomNavScrollPosition > 0) {
                        bottomNav.scrollLeft = bottomNavScrollPosition;
                    }

                    // Save scroll position when scrolling
                    bottomNav.addEventListener('scroll', function() {
                        bottomNavScrollPosition = this.scrollLeft;
                        sessionStorage.setItem('bottomNavScrollLeft', this.scrollLeft);
                    });

                    // Save scroll position before navigation
                    const navLinks = bottomNav.querySelectorAll('a, button');
                    navLinks.forEach(link => {
                        link.addEventListener('click', function(e) {
                            saveScrollPosition();
                        });
                    });
                } else {
                    // If element not found, try again in a moment
                    setTimeout(tryRestoreImmediate, 10);
                }
            }

            // Start trying immediately
            tryRestoreImmediate();

            // Handle Livewire navigation events
            document.addEventListener('livewire:navigating', function() {
                saveScrollPosition();
            });

            document.addEventListener('livewire:navigated', function() {
                // Restore immediately without delay
                restoreScrollPosition();
                // Also try with a small delay as backup
                setTimeout(restoreScrollPosition, 10);
            });
        </script>
    </body>
</html>
