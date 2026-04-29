<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-white/10 bg-brand-night/80 backdrop-blur-xl">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between gap-6">
            <div class="flex items-center gap-10">
                <div class="shrink-0">
                    <a href="{{ Route::has('admin.dishes.index') ? route('admin.dishes.index') : '#' }}">
                        <x-application-logo class="text-brand-cream" />
                    </a>
                </div>

                <div class="hidden items-center gap-3 sm:flex">
                    <x-nav-link :href="Route::has('admin.dishes.index') ? route('admin.dishes.index') : '#'" :active="request()->routeIs('admin.dishes.index')">
                        {{ __('Gestion de menu') }}
                    </x-nav-link>

                    <x-nav-link :href="Route::has('admin.orders.index') ? route('admin.orders.index') : '#'" :active="request()->routeIs('admin.orders.index')">
                        {{ __('Ver pedidos') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.statistics')" :active="request()->routeIs('admin.statistics')">
                        {{ __('Estadisticas') }}
                    </x-nav-link>

                    <a href="/" target="_blank" class="brand-nav-link border-brand-ember/50 bg-brand-ember/10 text-brand-cream hover:bg-brand-ember/20">
                        {{ __('Ir a la web') }}
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="56" contentClasses="brand-panel min-w-[14rem] p-2">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold uppercase tracking-[0.16em] text-brand-cream transition hover:border-brand-ember/50 hover:bg-brand-ember/10">
                            <div>{{ Auth::user()->name }}</div>
                            <div>
                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Cerrar sesion') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 p-2 text-brand-cream transition hover:border-brand-ember/50 hover:bg-brand-ember/10">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': ! open }" class="hidden sm:hidden">
        <div class="space-y-2 px-4 pb-4 pt-3">
            <x-responsive-nav-link :href="Route::has('admin.dishes.index') ? route('admin.dishes.index') : '#'" :active="request()->routeIs('admin.dishes.index')">
                {{ __('Gestion de menu') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="Route::has('admin.orders.index') ? route('admin.orders.index') : '#'" :active="request()->routeIs('admin.orders.index')">
                {{ __('Ver pedidos') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.statistics')" :active="request()->routeIs('admin.statistics')">
                {{ __('Estadisticas') }}
            </x-responsive-nav-link>

            <a href="/" target="_blank" class="block w-full rounded-2xl border border-brand-ember/50 bg-brand-ember/10 px-4 py-3 text-sm font-semibold uppercase tracking-[0.18em] text-brand-cream transition hover:bg-brand-ember/20">
                {{ __('Ir a la web') }}
            </a>
        </div>
    </div>
</nav>
