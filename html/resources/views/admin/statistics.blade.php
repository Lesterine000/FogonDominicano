@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="brand-kicker">Analitica comercial</p>
            <h1 class="mt-2 text-5xl leading-none text-brand-cream">Resumen de ganancias</h1>
            <p class="mt-3 text-sm text-brand-mist">Solo se cuentan pedidos pagados y entregados, filtrados por fecha de servicio.</p>
        </div>

        <a href="{{ route('admin.dishes.index') }}" class="brand-button-secondary">
            Volver al menu
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-[1.5rem] border border-brand-ember/40 bg-brand-ember/10 p-4 font-semibold text-brand-cream">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="brand-panel mb-6 p-5">
        <div class="mb-4 flex flex-wrap gap-3">
            <a href="{{ route('admin.statistics', ['period' => 'day']) }}"
               class="{{ $filter === 'day' ? 'brand-button' : 'brand-button-secondary' }}">
                Hoy
            </a>
            <a href="{{ route('admin.statistics', ['period' => 'month']) }}"
               class="{{ $filter === 'month' ? 'brand-button' : 'brand-button-secondary' }}">
                Este mes
            </a>
            <a href="{{ route('admin.statistics', ['period' => 'total']) }}"
               class="{{ $filter === 'total' ? 'brand-button' : 'brand-button-secondary' }}">
                Historico total
            </a>
        </div>

        <form action="{{ route('admin.statistics') }}" method="GET" class="grid gap-4 md:grid-cols-[1fr_1fr_auto] md:items-end">
            <input type="hidden" name="period" value="range">

            <div>
                <label for="from" class="brand-label">Desde</label>
                <input id="from" type="date" name="from" value="{{ $from }}" class="brand-input">
            </div>

            <div>
                <label for="to" class="brand-label">Hasta</label>
                <input id="to" type="date" name="to" value="{{ $to }}" class="brand-input">
            </div>

            <button type="submit" class="brand-button md:mb-[1px]">
                Filtrar
            </button>
        </form>
    </div>

    <div class="mb-6 grid gap-4 md:grid-cols-3">
        <div class="brand-panel p-6">
            <p class="brand-kicker">Periodo activo</p>
            <p class="mt-3 text-xl font-semibold text-brand-cream">{{ $filterLabel }}</p>
            <p class="mt-2 text-xs text-brand-mist">Basado en la fecha del menu servido.</p>
        </div>

        <div class="brand-panel p-6">
            <p class="brand-kicker">Pedidos contabilizados</p>
            <p class="mt-3 text-4xl font-semibold text-brand-gold">{{ $totalOrders }}</p>
        </div>

        <div class="brand-panel p-6">
            <p class="brand-kicker">Raciones vendidas</p>
            <p class="mt-3 text-4xl font-semibold text-brand-cream">{{ $totalServings }}</p>
        </div>
    </div>

    <div class="brand-panel mb-6 p-6">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="brand-kicker">Ganancia total generada</p>
                <h2 class="mt-2 text-5xl text-brand-cream">@euro($grandTotal)</h2>
            </div>
            <p class="text-sm text-brand-mist">Base de calculo: pedidos entregados, pagados y agrupados por menu servido.</p>
        </div>
    </div>

    <div class="brand-panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="border-b border-white/10 bg-brand-night/40">
                        <th class="px-6 py-4 text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-brand-mist">Plato</th>
                        <th class="px-6 py-4 text-center text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-brand-mist">Raciones vendidas</th>
                        <th class="px-6 py-4 text-right text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-brand-mist">Ganancia parcial</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($summary as $item)
                        <tr class="transition-colors hover:bg-white/5">
                            <td class="px-6 py-4 font-semibold text-brand-cream">{{ $item['dish_name'] }}</td>
                            <td class="px-6 py-4 text-center font-semibold text-brand-gold">{{ $item['total_sold'] }}</td>
                            <td class="px-6 py-4 text-right font-semibold text-brand-cream">@euro($item['total_revenue'])</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-16 text-center text-sm font-semibold text-brand-mist">
                                No se registran ganancias para los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
