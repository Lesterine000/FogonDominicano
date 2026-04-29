<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('dishes', 'price')) {
            return;
        }

        Schema::table('dishes', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->default(0.00)->after('name');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('dishes', 'price')) {
            return;
        }

        Schema::table('dishes', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
