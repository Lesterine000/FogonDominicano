<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Muestra el panel del menu diario.
     */
    public function index()
    {
        $today = today();
        $currentMenu = Dish::query()
            ->whereDate('service_date', $today)
            ->latest('updated_at')
            ->first();

        $menuHistory = Dish::query()
            ->orderByDesc('service_date')
            ->orderByDesc('id')
            ->get();

        return view('admin.index', compact('today', 'currentMenu', 'menuHistory'));
    }

    /**
     * Guarda un nuevo menu del dia.
     */
    /**
     * Guarda un nuevo menu del dia.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'service_date' => [
                'required',
                'date',
                Rule::unique('dishes', 'service_date')->where(fn ($query) => $query->whereNull('deleted_at')),
            ],
            'available_servings' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        // Se ha eliminado la validación de $dish->orders() de aquí 
        // porque en la creación el objeto $dish aún no existe.

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'service_date' => $validated['service_date'],
            'available_servings' => $validated['available_servings'],
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('dishes', 'public');
        }

        Dish::create($data);

        return redirect()->route('admin.dishes.index')->with('success', 'Menu del dia creado correctamente.');
    }
    /**
     * Actualiza un menu diario.
     */
    public function update(Request $request, $id)
    {
        $dish = Dish::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'service_date' => [
                'required',
                'date',
                Rule::unique('dishes', 'service_date')
                    ->ignore($dish->id)
                    ->where(fn ($query) => $query->whereNull('deleted_at')),
            ],
            'available_servings' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'service_date' => $validated['service_date'],
            'available_servings' => $validated['available_servings'],
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image')) {
            if ($dish->image) {
                Storage::disk('public')->delete($dish->image);
            }

            $data['image'] = $request->file('image')->store('dishes', 'public');
        }

        $dish->update($data);

        return redirect()->route('admin.dishes.index')->with('success', 'Menu actualizado correctamente.');
    }

    /**
     * Elimina un menu diario sin perder el historial de pedidos.
     */
    public function destroy($id)
    {
        $dish = Dish::findOrFail($id);

        if ($dish->image) {
            Storage::disk('public')->delete($dish->image);
        }

        $dish->delete();

        return redirect()->route('admin.dishes.index')->with('success', 'Menu eliminado correctamente.');
    }

    /**
     * Genera el historico de ganancias por periodo.
     */
    public function statistics(Request $request)
    {
        $validated = $request->validate([
            'period' => 'nullable|in:day,month,total,range',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
        ]);

        $filter = $validated['period'] ?? 'total';
        $from = $validated['from'] ?? null;
        $to = $validated['to'] ?? null;

        if ($from || $to) {
            $filter = 'range';
        }

        $query = Order::query()
            ->where('is_paid', true)
            ->where('status', 'completed');

        if ($filter === 'range') {
            if ($from) {
                $query->whereDate('service_date', '>=', $from);
            }

            if ($to) {
                $query->whereDate('service_date', '<=', $to);
            }
        } elseif ($filter === 'day') {
            $query->whereDate('service_date', today());
        } elseif ($filter === 'month') {
            $query->whereMonth('service_date', now()->month)
                ->whereYear('service_date', now()->year);
        }

        $orders = $query->orderByDesc('service_date')->latest()->get();

        $summary = $orders->groupBy('dish_name')->map(function ($group) {
            $totalQuantity = $group->sum('quantity');

            return [
                'dish_name' => $group->first()->dish_name ?? 'Plato desconocido',
                'total_sold' => $totalQuantity,
                'total_revenue' => $group->sum(function ($order) {
                    return $order->quantity * (float) $order->unit_price;
                }),
            ];
        })->sortByDesc('total_revenue')->values();

        $grandTotal = $summary->sum('total_revenue');
        $totalOrders = $orders->count();
        $totalServings = $orders->sum('quantity');

        $filterLabel = match ($filter) {
            'day' => 'Hoy',
            'month' => 'Este mes',
            'range' => $this->formatStatisticsRangeLabel($from, $to),
            default => 'Historico total',
        };

        return view('admin.statistics', compact(
            'summary',
            'grandTotal',
            'filter',
            'filterLabel',
            'from',
            'to',
            'totalOrders',
            'totalServings'
        ));
    }

    private function formatStatisticsRangeLabel(?string $from, ?string $to): string
    {
        if ($from && $to) {
            return 'Del ' . Carbon::parse($from)->format('d/m/Y') . ' al ' . Carbon::parse($to)->format('d/m/Y');
        }

        if ($from) {
            return 'Desde ' . Carbon::parse($from)->format('d/m/Y');
        }

        if ($to) {
            return 'Hasta ' . Carbon::parse($to)->format('d/m/Y');
        }

        return 'Rango personalizado';
    }
}
