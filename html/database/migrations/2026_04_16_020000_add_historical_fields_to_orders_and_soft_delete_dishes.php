<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('dish_name')->nullable()->after('dish_id');
            $table->decimal('unit_price', 8, 2)->default(0)->after('dish_name');
        });

        DB::table('orders')
            ->leftJoin('dishes', 'orders.dish_id', '=', 'dishes.id')
            ->select('orders.id', 'dishes.name', 'dishes.price')
            ->get()
            ->each(function (object $order): void {
                DB::table('orders')
                    ->where('id', $order->id)
                    ->update([
                        'dish_name' => $order->name,
                        'unit_price' => $order->price ?? 0,
                    ]);
            });

        Schema::table('dishes', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('dishes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['dish_name', 'unit_price']);
        });
    }
};
