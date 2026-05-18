<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     */
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->boolean('is_paid')->default(false)->after('status');
    });
}

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('is_paid');
        });
    }
};
