<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            //Relacionamos con el plato que se esta ordenando
            $table->foreignId('dish_id')->constrained()->restrictOnDelete();

            //Datos del cliente
            $table ->string('customer_name');
            $table->string('customer_phone');
            $table->text('pickup_time');

            //Estado del pedido: "pending", "completed", "cancelled"
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
