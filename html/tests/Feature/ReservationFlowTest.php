<?php

namespace Tests\Feature;

use App\Mail\ReservationConfirmed;
use App\Models\Dish;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReservationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservation_sends_confirmation_mail_immediately_when_mail_delivery_is_sync(): void
    {
        config()->set('restaurant.reservation_mail_delivery', 'sync');
        Mail::fake();

        $dish = Dish::create([
            'name' => 'Pastelon',
            'description' => 'Platano maduro y carne',
            'price' => 11.50,
            'service_date' => today()->toDateString(),
            'available_servings' => 5,
            'is_active' => true,
        ]);

        $response = $this->post(route('reserve'), [
            'dish_id' => $dish->id,
            'customer_name' => 'Cliente Demo',
            'customer_email' => 'cliente@example.com',
            'customer_phone' => '600000123',
            'quantity' => 2,
            'pickup_time' => '13:00',
            'privacy' => '1',
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('orders', [
            'dish_id' => $dish->id,
            'customer_email' => 'cliente@example.com',
            'quantity' => 2,
        ]);
        $this->assertSame(3, $dish->fresh()->available_servings);
        Mail::assertSent(ReservationConfirmed::class);
        Mail::assertNotQueued(ReservationConfirmed::class);
    }

    public function test_reservation_queues_confirmation_mail_when_mail_delivery_is_queue(): void
    {
        config()->set('restaurant.reservation_mail_delivery', 'queue');
        Mail::fake();

        $dish = Dish::create([
            'name' => 'La Bandera',
            'description' => 'Arroz, habichuelas y carne',
            'price' => 13.00,
            'service_date' => today()->toDateString(),
            'available_servings' => 4,
            'is_active' => true,
        ]);

        $response = $this->post(route('reserve'), [
            'dish_id' => $dish->id,
            'customer_name' => 'Cliente Cola',
            'customer_email' => 'cola@example.com',
            'customer_phone' => '600000456',
            'quantity' => 1,
            'pickup_time' => '14:00',
            'privacy' => '1',
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success');
        Mail::assertQueued(ReservationConfirmed::class);
        Mail::assertNotSent(ReservationConfirmed::class);
    }
}
