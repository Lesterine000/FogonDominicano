<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     */
    public function up(): void
    {
    Schema::create('inventories', function (Blueprint $table) {
        $table->id();
        $table->foreignId('dish_id')->constrained()->onDelete('cascade');
        $table->date('available_at'); // Para qué día es este stock
        $table->integer('quantity');  // Cuántas raciones se hicieron
        $table->integer('reserved')->default(0); // Cuántas se han vendido
        $table->timestamps();
});
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
