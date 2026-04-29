<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('dishes', 'service_date')) {
            Schema::table('dishes', function (Blueprint $table) {
                $table->date('service_date')->nullable();
            });
        }

        if (!Schema::hasColumn('orders', 'service_date')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->date('service_date')->nullable();
            });
        }

        DB::table('dishes')
            ->whereNull('service_date')
            ->update(['service_date' => DB::raw('date(created_at)')]);

        DB::table('orders')
            ->whereNull('service_date')
            ->update(['service_date' => DB::raw('date(created_at)')]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'service_date')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('service_date');
            });
        }

        if (Schema::hasColumn('dishes', 'service_date')) {
            Schema::table('dishes', function (Blueprint $table) {
                $table->dropColumn('service_date');
            });
        }
    }
};
