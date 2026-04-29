<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Muestra la lista de pedidos filtrada por fecha de servicio.
     */
    public function index(Request $request)
    {
        $fechaSeleccionada = $request->query('fecha', today()->toDateString());

        $orders = Order::with('dish')
            ->whereDate('service_date', $fechaSeleccionada)
            ->latest()
            ->get();

        return view('admin.orders', compact('orders', 'fechaSeleccionada'));
    }

    /**
     * Actualiza el estado del pedido.
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->status === 'cancelled') {
            return redirect()->back()->with('error', 'El pedido ya esta cancelado y no admite mas cambios de estado.');
        }

        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        if ($request->status === 'cancelled') {
            return $this->cancelOrder($order);
        }

        $order->update([
            'status' => $request->status,
            'status_updated_at' => now(),
            'status_updated_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', "El pedido de {$order->customer_name} ha sido actualizado.");
    }

    /**
     * Cambia el estado de pago.
     */
    public function togglePayment($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status === 'cancelled') {
            return redirect()->back()->with('error', 'No puedes cambiar el pago de un pedido cancelado.');
        }

        $markAsPaid = ! $order->is_paid;

        $order->update([
            'is_paid' => $markAsPaid,
            'paid_at' => $markAsPaid ? now() : null,
            'paid_by' => $markAsPaid ? Auth::id() : null,
        ]);

        $mensaje = $order->is_paid ? 'Pedido marcado como PAGADO.' : 'Pedido marcado como PENDIENTE DE PAGO.';

        return redirect()->back()->with('success', $mensaje);
    }

    /**
     * Cancela una reserva sin borrar el historial.
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'cancelled' && $order->dish && ! $order->dish->trashed()) {
            $order->dish->increment('available_servings', $order->quantity);
        }

        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Pedido eliminado y stock actualizado.');
    }

    private function cancelOrder(Order $order)
    {
        if ($order->status === 'cancelled') {
            return redirect()->back()->with('error', 'El pedido ya estaba cancelado.');
        }

        if ($order->dish && ! $order->dish->trashed()) {
            $order->dish->increment('available_servings', $order->quantity);
        }

        $order->update([
            'status' => 'cancelled',
            'status_updated_at' => now(),
            'status_updated_by' => Auth::id(),
            'cancelled_at' => now(),
            'cancelled_by' => Auth::id(),
        ]);

        return redirect()->route('admin.orders.index')->with('success', 'Pedido cancelado y stock actualizado.');
    }
}
