<?php

namespace Tests\Feature;

use App\Models\Dish;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_order_status_with_lowercase_values(): void
    {
        $admin = User::factory()->admin()->create();
        $dish = Dish::create([
            'name' => 'La Bandera',
            'description' => 'Arroz, habichuelas y carne',
            'price' => 12.50,
            'service_date' => today()->toDateString(),
            'available_servings' => 8,
            'is_active' => true,
        ]);

        $order = Order::create([
            'dish_id' => $dish->id,
            'dish_name' => $dish->name,
            'unit_price' => $dish->price,
            'service_date' => $dish->service_date,
            'customer_name' => 'Cliente Demo',
            'customer_email' => 'cliente@example.com',
            'customer_phone' => '600000000',
            'quantity' => 2,
            'pickup_time' => '13:00',
            'status' => 'pending',
            'is_paid' => false,
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(route('admin.orders.updateStatus', $order->id), [
                'status' => 'completed',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertSame('completed', $order->fresh()->status);
    }

    public function test_deleting_an_order_restores_stock_and_redirects_to_orders_index(): void
    {
        $admin = User::factory()->admin()->create();
        $dish = Dish::create([
            'name' => 'Sancocho',
            'description' => 'Tradicional',
            'price' => 14.00,
            'service_date' => today()->toDateString(),
            'available_servings' => 3,
            'is_active' => true,
        ]);

        $order = Order::create([
            'dish_id' => $dish->id,
            'dish_name' => $dish->name,
            'unit_price' => $dish->price,
            'service_date' => $dish->service_date,
            'customer_name' => 'Cliente Demo',
            'customer_email' => 'cliente@example.com',
            'customer_phone' => '600000000',
            'quantity' => 2,
            'pickup_time' => '14:30',
            'status' => 'pending',
            'is_paid' => false,
        ]);

        $response = $this
            ->actingAs($admin)
            ->delete(route('admin.orders.destroy', $order->id));

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
        $this->assertSame(5, $dish->fresh()->available_servings);
    }

    public function test_admin_can_soft_delete_a_menu_without_losing_historical_orders(): void
    {
        $admin = User::factory()->admin()->create();
        $dish = Dish::create([
            'name' => 'Mofongo',
            'description' => 'Con chicharron',
            'price' => 13.50,
            'service_date' => today()->toDateString(),
            'available_servings' => 10,
            'is_active' => true,
        ]);

        $order = Order::create([
            'dish_id' => $dish->id,
            'dish_name' => 'Mofongo',
            'unit_price' => 13.50,
            'service_date' => today()->toDateString(),
            'customer_name' => 'Cliente Demo',
            'customer_email' => 'cliente@example.com',
            'customer_phone' => '600000000',
            'quantity' => 1,
            'pickup_time' => '15:00',
            'status' => 'completed',
            'is_paid' => true,
        ]);

        $response = $this
            ->actingAs($admin)
            ->delete(route('admin.destroy', $dish->id));

        $response->assertRedirect(route('admin.dishes.index'));
        $response->assertSessionHas('success');
        $this->assertSoftDeleted('dishes', ['id' => $dish->id]);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'dish_name' => 'Mofongo']);
    }

    public function test_statistics_only_include_paid_and_completed_orders(): void
    {
        $admin = User::factory()->admin()->create();
        $dish = Dish::create([
            'name' => 'Chofan',
            'description' => 'Arroz salteado',
            'price' => 16.00,
            'service_date' => today()->toDateString(),
            'available_servings' => 12,
            'is_active' => true,
        ]);

        Order::create([
            'dish_id' => $dish->id,
            'dish_name' => 'Chofan',
            'unit_price' => 16.00,
            'service_date' => today()->toDateString(),
            'customer_name' => 'Pago listo',
            'customer_email' => 'pagado@example.com',
            'customer_phone' => '600000001',
            'quantity' => 2,
            'pickup_time' => '13:00',
            'status' => 'completed',
            'is_paid' => true,
        ]);

        Order::create([
            'dish_id' => $dish->id,
            'dish_name' => 'Chofan',
            'unit_price' => 16.00,
            'service_date' => today()->toDateString(),
            'customer_name' => 'No pagado',
            'customer_email' => 'nopagado@example.com',
            'customer_phone' => '600000002',
            'quantity' => 5,
            'pickup_time' => '14:00',
            'status' => 'completed',
            'is_paid' => false,
        ]);

        Order::create([
            'dish_id' => $dish->id,
            'dish_name' => 'Chofan',
            'unit_price' => 16.00,
            'service_date' => today()->toDateString(),
            'customer_name' => 'No entregado',
            'customer_email' => 'pendiente@example.com',
            'customer_phone' => '600000003',
            'quantity' => 4,
            'pickup_time' => '15:00',
            'status' => 'pending',
            'is_paid' => true,
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.statistics'));

        $response->assertOk();
        $response->assertSee('32,00 €');
        $response->assertDontSee('176,00 €');
        $response->assertSee('Solo se cuentan pedidos pagados y entregados, filtrados por fecha de servicio.');
    }

    public function test_statistics_can_be_filtered_by_custom_service_date_range(): void
    {
        $admin = User::factory()->admin()->create();
        $dish = Dish::create([
            'name' => 'Asopao',
            'description' => 'Caldoso',
            'price' => 12.00,
            'service_date' => '2026-04-10',
            'available_servings' => 10,
            'is_active' => true,
        ]);

        Order::create([
            'dish_id' => $dish->id,
            'dish_name' => 'Asopao',
            'unit_price' => 12.00,
            'service_date' => '2026-04-10',
            'customer_name' => 'Dentro',
            'customer_email' => 'dentro@example.com',
            'customer_phone' => '600000004',
            'quantity' => 2,
            'pickup_time' => '13:00',
            'status' => 'completed',
            'is_paid' => true,
        ]);

        Order::create([
            'dish_id' => $dish->id,
            'dish_name' => 'Asopao',
            'unit_price' => 12.00,
            'service_date' => '2026-05-01',
            'customer_name' => 'Fuera',
            'customer_email' => 'fuera@example.com',
            'customer_phone' => '600000005',
            'quantity' => 3,
            'pickup_time' => '14:00',
            'status' => 'completed',
            'is_paid' => true,
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.statistics', [
                'period' => 'range',
                'from' => '2026-04-01',
                'to' => '2026-04-30',
            ]));

        $response->assertOk();
        $response->assertSee('Del 01/04/2026 al 30/04/2026');
        $response->assertSee('24,00 €');
        $response->assertDontSee('60,00 €');
    }

    public function test_statistics_range_validation_rejects_end_date_before_start_date(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this
            ->actingAs($admin)
            ->from(route('admin.statistics'))
            ->get(route('admin.statistics', [
                'period' => 'range',
                'from' => '2026-04-30',
                'to' => '2026-04-01',
            ]));

        $response->assertRedirect(route('admin.statistics'));
        $response->assertSessionHasErrors('to');
    }

    public function test_orders_are_filtered_by_service_date(): void
    {
        $admin = User::factory()->admin()->create();
        $dish = Dish::create([
            'name' => 'Pasteles en hoja',
            'description' => 'Tradicional',
            'price' => 9.50,
            'service_date' => '2026-04-11',
            'available_servings' => 8,
            'is_active' => true,
        ]);

        Order::create([
            'dish_id' => $dish->id,
            'dish_name' => 'Pasteles en hoja',
            'unit_price' => 9.50,
            'service_date' => '2026-04-11',
            'customer_name' => 'Cliente Demo',
            'customer_email' => 'cliente@example.com',
            'customer_phone' => '600000000',
            'quantity' => 1,
            'pickup_time' => '12:30',
            'status' => 'completed',
            'is_paid' => true,
        ]);

        Order::create([
            'dish_id' => $dish->id,
            'dish_name' => 'Pasteles en hoja',
            'unit_price' => 9.50,
            'service_date' => '2026-04-12',
            'customer_name' => 'Otro cliente',
            'customer_email' => 'otro@example.com',
            'customer_phone' => '600000111',
            'quantity' => 1,
            'pickup_time' => '13:00',
            'status' => 'completed',
            'is_paid' => true,
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.orders.index', ['fecha' => '2026-04-11']));

        $response->assertOk();
        $response->assertSee('Cliente Demo');
        $response->assertDontSee('Otro cliente');
    }

    public function test_homepage_shows_only_todays_active_menu(): void
    {
        $todayDish = Dish::create([
            'name' => 'Pastelon',
            'description' => 'Platano maduro y carne',
            'price' => 11.00,
            'service_date' => today()->toDateString(),
            'available_servings' => 4,
            'is_active' => true,
        ]);

        Dish::create([
            'name' => 'Locrio',
            'description' => 'Arroz meloso',
            'price' => 10.00,
            'service_date' => today()->addDay()->toDateString(),
            'available_servings' => 6,
            'is_active' => true,
        ]);

        Dish::create([
            'name' => 'Oculto',
            'description' => 'No deberia salir',
            'price' => 9.00,
            'service_date' => today()->toDateString(),
            'available_servings' => 6,
            'is_active' => false,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee($todayDish->name);
        $response->assertDontSee('Locrio');
        $response->assertDontSee('Oculto');
    }

    public function test_homepage_shows_sold_out_message_when_today_menu_has_no_servings(): void
    {
        Dish::create([
            'name' => 'Chivo guisado',
            'description' => 'Agotado',
            'price' => 18.00,
            'service_date' => today()->toDateString(),
            'available_servings' => 0,
            'is_active' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('No mas raciones por hoy');
    }
}
