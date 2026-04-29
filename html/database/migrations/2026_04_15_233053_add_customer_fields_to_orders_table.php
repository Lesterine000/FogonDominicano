<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        // Añadimos las columnas que faltan según el error de la imagen
        if (!Schema::hasColumn('orders', 'customer_name')) {
            $table->string('customer_name')->after('dish_id');
        }
        if (!Schema::hasColumn('orders', 'customer_email')) {
            $table->string('customer_email')->after('customer_name');
        }
        if (!Schema::hasColumn('orders', 'customer_phone')) {
            $table->string('customer_phone')->after('customer_email');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'customer_email')) {
                $table->dropColumn('customer_email');
            }
        });
    }
};
