@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="brand-kicker">Control de reservas</p>
            <h1 class="mt-2 text-5xl leading-none text-brand-cream">Gestion de pedidos</h1>
            <p class="mt-3 text-sm text-brand-mist">Seguimiento diario de clientes, entregas, cobros e importe en euros.</p>
        </div>

        <form action="{{ route('admin.orders.index') }}" method="GET" class="brand-panel flex items-center gap-3 px-4 py-3">
            <span class="brand-kicker">Calendario</span>
            <input type="date" name="fecha" id="fecha"
                   value="{{ $fechaSeleccionada }}"
                   class="rounded-full border border-white/10 bg-brand-night/70 px-4 py-2 font-semibold text-brand-cream focus:border-brand-ember focus:ring-brand-ember"
                   onchange="this.form.submit()">
        </form>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-[1.5rem] border border-brand-sage/30 bg-brand-sage/10 p-4 font-semibold text-brand-cream">
            {{ session('success') }}
        </div>
    @endif

    <div class="brand-panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="border-b border-white/10 bg-brand-night/40">
                        <th class="px-6 py-4 text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-brand-mist">Cliente</th>
                        <th class="px-6 py-4 text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-brand-mist">Plato</th>
                        <th class="px-6 py-4 text-center text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-brand-mist">Cant.</th>
                        <th class="px-6 py-4 text-center text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-brand-mist">Hora</th>
                        <th class="px-6 py-4 text-right text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-brand-mist">Importe</th>
                        <th class="px-6 py-4 text-center text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-brand-mist">Pago</th>
                        <th class="px-6 py-4 text-center text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-brand-mist">Estado</th>
                        <th class="px-6 py-4 text-right text-[0.65rem] font-semibold uppercase tracking-[0.28em] text-brand-mist">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($orders as $order)
                        @php
                            $orderAmount = $order->quantity * (float) ($order->unit_price ?? $order->dish?->price ?? 0);
                        @endphp
                        <tr class="transition-colors hover:bg-white/5">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-brand-cream">{{ $order->customer_name }}</div>
                                <div class="mt-1 text-xs uppercase tracking-[0.18em] text-brand-mist">{{ $order->customer_phone }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-brand-gold">
                                {{ $order->dish_name ?? $order->dish?->name ?? 'Plato eliminado' }}
                            </td>
                            <td class="px-6 py-4 text-center font-semibold text-brand-cream">
                                {{ $order->quantity }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex rounded-full border border-white/10 bg-brand-night/60 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-brand-cream">
                                    {{ $order->pickup_time }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-semibold text-brand-cream">
                                @euro($orderAmount)
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.orders.togglePayment', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1 rounded-full text-[10px] font-semibold uppercase tracking-[0.22em] transition-all {{ $order->is_paid ? 'bg-brand-sage text-white' : 'border border-brand-ember/40 bg-brand-ember/10 text-brand-cream hover:bg-brand-ember hover:text-white' }}">
                                        {{ $order->is_paid ? 'Pagado' : 'Pendiente' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()"
                                            class="rounded-full border border-white/10 bg-brand-night/70 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] focus:border-brand-ember focus:ring-brand-ember {{ $order->status === 'completed' ? 'text-brand-sage' : ($order->status === 'cancelled' ? 'text-red-300' : 'text-brand-gold') }}">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Entregado</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Eliminar reserva?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 p-2 text-brand-mist transition hover:border-brand-ember/40 hover:text-brand-ember">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="mb-4 h-12 w-12 text-brand-mist/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-brand-mist">No hay pedidos para este dia</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
