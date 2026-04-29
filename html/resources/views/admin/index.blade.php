<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="brand-kicker">Panel de producto</p>
                <h2 class="text-4xl leading-none text-brand-cream">
                    {{ __('Menu del dia') }}
                </h2>
            </div>
            <span class="brand-badge border-brand-gold/40 bg-brand-gold/10 text-brand-cream">Precios en euro</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="brand-panel p-6">
                <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="brand-kicker">Cocina organizada</p>
                        <h1 class="mt-2 text-4xl leading-none text-brand-cream">
                            Gestion del menu diario
                        </h1>
                        <p class="mt-3 text-sm text-brand-mist">
                            Solo puede existir un menu por fecha. El cliente solo ve el menu activo del dia.
                        </p>
                    </div>

                    <button type="button" class="brand-button" data-bs-toggle="modal" data-bs-target="#modalNuevoPlato">
                        Programar menu
                    </button>
                </div>

                @if(session('success'))
                    <div class="mb-6 rounded-xl border border-green-500/40 bg-green-500/10 p-4 font-bold text-green-300">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 rounded-xl border border-red-500/40 bg-red-500/10 p-4 font-bold text-red-300">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 rounded-xl border border-red-500/40 bg-red-500/10 p-4 font-bold text-red-300">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="mb-8 rounded-[2rem] border border-gray-700 bg-gray-800 p-6 shadow-xl">
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-500">Menu activo hoy</p>
                            <p class="text-sm text-gray-400">{{ $today->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    @if($currentMenu)
                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[260px_1fr]">
                            <div class="overflow-hidden rounded-2xl bg-gray-900">
                                @if($currentMenu->image)
                                    <img src="{{ asset('storage/' . $currentMenu->image) }}" alt="{{ $currentMenu->name }}" class="h-full min-h-[220px] w-full object-cover">
                                @else
                                    <div class="flex h-full min-h-[220px] items-center justify-center text-xs font-bold uppercase text-gray-600">
                                        Sin imagen disponible
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col justify-between gap-4">
                                <div>
                                    <div class="mb-3 flex flex-wrap gap-2">
                                        <span class="rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-widest {{ $currentMenu->is_active ? 'bg-green-500/20 text-green-300' : 'bg-gray-700 text-gray-300' }}">
                                            {{ $currentMenu->is_active ? 'Activo' : 'Oculto' }}
                                        </span>
                                        <span class="rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-widest {{ $currentMenu->available_servings > 0 ? 'bg-brand-gold/20 text-brand-gold' : 'bg-red-500/20 text-red-300' }}">
                                            {{ $currentMenu->available_servings > 0 ? 'Disponible' : 'Agotado' }}
                                        </span>
                                    </div>

                                    <h3 class="text-3xl font-black uppercase tracking-tighter text-white">{{ $currentMenu->name }}</h3>
                                    <p class="mt-3 max-w-2xl text-sm text-gray-400">
                                        {{ $currentMenu->description ?: 'Sin descripcion.' }}
                                    </p>
                                </div>

                                <div class="grid gap-4 md:grid-cols-3">
                                    <div class="rounded-2xl border border-gray-700 bg-gray-900/50 p-4">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-500">Precio</p>
                                        <p class="mt-2 text-2xl font-black text-brand-cream">@euro($currentMenu->price)</p>
                                    </div>
                                    <div class="rounded-2xl border border-gray-700 bg-gray-900/50 p-4">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-500">Raciones</p>
                                        <p class="mt-2 text-2xl font-black text-brand-gold">{{ $currentMenu->available_servings }}</p>
                                    </div>
                                    <div class="rounded-2xl border border-gray-700 bg-gray-900/50 p-4">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-500">Fecha</p>
                                        <p class="mt-2 text-2xl font-black text-white">{{ $currentMenu->service_date->format('d/m') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-gray-700 bg-gray-900/40 p-8 text-center">
                            <p class="text-sm font-bold uppercase tracking-widest text-gray-500">No has configurado un menu para hoy.</p>
                        </div>
                    @endif
                </div>

                <div>
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-black uppercase tracking-widest text-white">Calendario de menus</h3>
                        <span class="text-xs font-bold uppercase tracking-widest text-gray-500">{{ $menuHistory->count() }} registrados</span>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @forelse($menuHistory as $dish)
                            <div class="overflow-hidden rounded-xl border border-gray-700 bg-gray-800 shadow-xl shadow-black/50">
                                <div class="relative h-48 w-full bg-gray-900">
                                    @if($dish->image)
                                        <img src="{{ asset('storage/' . $dish->image) }}" alt="{{ $dish->name }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full items-center justify-center text-gray-600">
                                            <span class="text-xs font-bold uppercase">Sin imagen disponible</span>
                                        </div>
                                    @endif

                                    <div class="absolute left-4 top-4 rounded-full bg-gray-950/80 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-white">
                                        {{ $dish->service_date->format('d/m/Y') }}
                                    </div>
                                </div>

                                <div class="p-6">
                                    <div class="mb-4 flex items-start justify-between gap-3">
                                        <h3 class="text-xl font-bold text-white">{{ $dish->name }}</h3>
                                        <span class="rounded-full border border-brand-ember/30 bg-brand-ember/10 px-3 py-1 text-sm font-semibold text-brand-cream">
                                            @euro($dish->price)
                                        </span>
                                    </div>

                                    <p class="mb-4 h-12 overflow-hidden text-sm text-gray-400">
                                        {{ $dish->description ?? 'Sin descripcion disponible.' }}
                                    </p>

                                    <div class="mb-6 flex items-center justify-between rounded-lg border border-gray-700 bg-gray-900/50 p-3">
                                        <span class="text-xs font-bold uppercase text-gray-400">Raciones</span>
                                        <span class="text-2xl font-black {{ $dish->available_servings > 0 ? 'text-green-400' : 'text-red-500' }}">
                                            {{ $dish->available_servings }}
                                        </span>
                                    </div>

                                    <div class="mb-6 flex gap-2">
                                        <span class="rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-widest {{ $dish->is_active ? 'bg-green-500/20 text-green-300' : 'bg-gray-700 text-gray-300' }}">
                                            {{ $dish->is_active ? 'Activo' : 'Oculto' }}
                                        </span>
                                        @if($dish->service_date->isToday())
                                            <span class="rounded-full bg-brand-ember/20 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-brand-cream">
                                                Hoy
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex gap-2">
                                        <button type="button" class="flex-1 rounded-full border border-white/10 bg-white/5 py-2 text-sm font-bold text-white transition hover:border-brand-gold/40 hover:text-brand-gold" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $dish->id }}">
                                            Editar
                                        </button>

                                        <form action="{{ route('admin.destroy', $dish->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Estas seguro de eliminar este menu?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full rounded border border-red-600/50 bg-red-600/20 py-2 text-sm font-bold text-red-500 transition hover:bg-red-600 hover:text-white">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <div class="col-span-full rounded-2xl border border-dashed border-gray-700 bg-gray-800/60 p-12 text-center text-sm font-bold uppercase tracking-widest text-gray-500">
                                No hay menus registrados todavia.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($menuHistory as $dish)
        <div class="modal fade" id="modalEditar{{ $dish->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-gray-700 bg-gray-800 text-white">
                    <form action="{{ route('admin.update', $dish->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-gray-700">
                            <h5 class="modal-title font-bold">Editar menu: {{ $dish->name }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body space-y-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-400">Nombre del plato</label>
                                <input type="text" name="name" value="{{ $dish->name }}" class="w-full rounded-lg border-gray-700 bg-gray-900 p-3 text-white focus:ring-brand-ember" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-400">Fecha de servicio</label>
                                <input type="date" name="service_date" value="{{ $dish->service_date->toDateString() }}" class="w-full rounded-lg border-gray-700 bg-gray-900 p-3 text-white focus:ring-brand-ember" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-400">Descripcion</label>
                                <textarea name="description" rows="3" class="w-full rounded-lg border-gray-700 bg-gray-900 p-3 text-white focus:ring-brand-ember">{{ $dish->description }}</textarea>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-400">Cambiar imagen (opcional)</label>
                                <input type="file" name="image" class="w-full rounded-lg border-gray-700 bg-gray-900 p-2 text-white" accept="image/*">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-400">Raciones en stock</label>
                                    <input type="number" name="available_servings" value="{{ $dish->available_servings }}" class="w-full rounded-lg border-gray-700 bg-gray-900 p-3 text-white" required min="0">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-400">Precio de venta (€)</label>
                                    <input type="number" step="0.01" name="price" value="{{ $dish->price }}" class="w-full rounded-lg border-gray-700 bg-gray-900 p-3 text-white" required min="0">
                                </div>
                            </div>
                            <label class="flex items-center gap-3 rounded-lg border border-gray-700 bg-gray-900/50 p-3">
                                <input type="checkbox" name="is_active" value="1" {{ $dish->is_active ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-700 bg-gray-900 text-brand-ember focus:ring-brand-ember">
                                <span class="text-sm font-bold text-gray-200">Mostrar este menu al cliente en su fecha</span>
                            </label>
                        </div>
                        <div class="modal-footer border-gray-700">
                            <button type="button" class="px-4 py-2 text-gray-400" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="rounded-full bg-brand-ember px-6 py-2 font-bold text-white transition hover:bg-brand-ember-dark">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="modalNuevoPlato" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-gray-700 bg-gray-800 text-white shadow-2xl">
                <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-gray-700">
                        <h5 class="modal-title font-black text-brand-cream">PROGRAMAR MENU DIARIO</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body space-y-4">
                        <div>
                            <label class="mb-1 block text-sm font-bold text-gray-400">Nombre</label>
                            <input type="text" name="name" class="w-full rounded-lg border border-gray-700 bg-gray-900 p-3 text-white outline-none focus:border-brand-ember" placeholder="Ej: Mofongo con Camarones" required>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-bold text-gray-400">Fecha de servicio</label>
                            <input type="date" name="service_date" value="{{ old('service_date', $today->toDateString()) }}" class="w-full rounded-lg border border-gray-700 bg-gray-900 p-3 text-white outline-none focus:border-brand-ember" required>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-bold text-gray-400">Imagen del plato</label>
                            <input type="file" name="image" class="w-full rounded-lg border border-gray-700 bg-gray-900 p-3 text-white outline-none focus:border-brand-ember" accept="image/*">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-bold text-gray-400">Descripcion (opcional)</label>
                            <textarea name="description" rows="2" class="w-full rounded-lg border border-gray-700 bg-gray-900 p-3 text-white outline-none focus:border-brand-ember"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-sm font-bold text-gray-400">Precio de venta (€)</label>
                                <input type="number" step="0.01" name="price" class="w-full rounded-lg border border-gray-700 bg-gray-900 p-3 text-white outline-none focus:border-brand-ember" required min="0">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-bold text-gray-400">Raciones iniciales</label>
                                <input type="number" name="available_servings" class="w-full rounded-lg border border-gray-700 bg-gray-900 p-3 text-white outline-none focus:border-brand-ember" required min="0">
                            </div>
                        </div>
                        <label class="flex items-center gap-3 rounded-lg border border-gray-700 bg-gray-900/50 p-3">
                            <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 rounded border-gray-700 bg-gray-900 text-brand-ember focus:ring-brand-ember">
                            <span class="text-sm font-bold text-gray-200">Mostrar este menu al cliente en su fecha</span>
                        </label>
                    </div>
                    <div class="modal-footer border-gray-700">
                        <button type="button" class="px-4 py-2 font-bold text-gray-400" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="rounded-full bg-brand-ember px-8 py-2 font-bold text-white shadow-lg transition hover:scale-105 hover:bg-brand-ember-dark">
                            Guardar menu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
