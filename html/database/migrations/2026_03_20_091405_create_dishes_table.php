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
        Schema::create('dishes', function (Blueprint $table) {
            $table->id();
            $table->string('name');             // Nombre del plato (Sancocho, etc.)
            $table->text('description')->nullable(); // Descripción opcional
            $table->decimal('price', 8, 2);      // Precio (ejemplo: 15.50)
            $table->string('image')->nullable();  // Ruta de la imagen
            $table->boolean('is_active')->default(true); // Estado de disponibilidad
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('dishes');
    }
};
