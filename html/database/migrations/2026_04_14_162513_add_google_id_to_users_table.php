<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Añadimos el campo google_id. 
            // Es 'nullable' porque los usuarios antiguos o los que entren por email no lo tendrán.
            // Es 'unique' para que no haya dos cuentas vinculadas al mismo ID de Google.
            $table->string('google_id')->nullable()->unique()->after('password');
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Esto permite deshacer el cambio si algo sale mal
            $table->dropColumn('google_id');
        });
    }
};