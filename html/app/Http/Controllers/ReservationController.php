<?php

namespace App\Http\Controllers;

use App\Mail\ReservationConfirmed;
use App\Models\Dish;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{
    public function index()
    {
        $dish = Dish::query()
            ->where('is_active', true)
            ->whereDate('service_date', today())
            ->latest('updated_at')
            ->first();

        $isSoldOut = ! $dish || $dish->available_servings < 1;

        return view('welcome', compact('dish', 'isSoldOut'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dish_id' => [
                'required',
                Rule::exists('dishes', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at')
                        ->where('is_active', true)
                        ->whereDate('service_date', today());
                }),
            ],
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'quantity' => 'required|integer|min:1',
            'pickup_time' => [
                'required',
                'date_format:H:i',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $pickupTime = Carbon::createFromFormat('H:i', $value);
                    $start = Carbon::createFromFormat('H:i', config('restaurant.pickup_time_start'));
                    $end = Carbon::createFromFormat('H:i', config('restaurant.pickup_time_end'));

                    if ($pickupTime->lt($start) || $pickupTime->gt($end)) {
                        $fail('La hora de recogida debe estar dentro del horario disponible del menu.');
                    }
                },
            ],
            'privacy' => 'required|accepted',
        ]);

        try {
            $order = DB::transaction(function () use ($validated) {
                $dish = Dish::query()
                    ->whereKey($validated['dish_id'])
                    ->where('is_active', true)
                    ->whereDate('service_date', today())
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($dish->available_servings < $validated['quantity']) {
                    throw new \RuntimeException("Lo sentimos, solo quedan {$dish->available_servings} raciones disponibles.");
                }

                $newOrder = Order::create([
                    'dish_id' => $dish->id,
                    'dish_name' => $dish->name,
                    'unit_price' => $dish->price,
                    'service_date' => $dish->service_date,
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'quantity' => $validated['quantity'],
                    'pickup_time' => $validated['pickup_time'],
                    'status' => 'pending',
                    'is_paid' => false,
                ]);

                $dish->decrement('available_servings', $validated['quantity']);

                return $newOrder;
            });

            try {
                $mailable = new ReservationConfirmed($order);
                $deliveryMode = strtolower((string) config('restaurant.reservation_mail_delivery', 'sync'));

                if ($deliveryMode === 'queue') {
                    Mail::to($order->customer_email)->queue($mailable);
                } else {
                    Mail::to($order->customer_email)->send($mailable);
                }
            } catch (\Exception $e) {
                Log::error("Fallo envio Brevo (Reserva #{$order->id}): " . $e->getMessage());
            }

            $orderId = '#FOG-' . str_pad($order->id, 4, '0', STR_PAD_LEFT);

            return redirect()->route('home')->with('success', "Reserva {$orderId} confirmada.");
        } catch (\Exception $e) {
            Log::error('Fallo critico en reserva: ' . $e->getMessage());

            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
