<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('is_paid');
            }

            if (!Schema::hasColumn('orders', 'paid_by')) {
                $table->foreignId('paid_by')->nullable()->after('paid_at')->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('orders', 'status_updated_at')) {
                $table->timestamp('status_updated_at')->nullable()->after('paid_by');
            }

            if (!Schema::hasColumn('orders', 'status_updated_by')) {
                $table->foreignId('status_updated_by')->nullable()->after('status_updated_at')->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('orders', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('status_updated_by');
            }

            if (!Schema::hasColumn('orders', 'cancelled_by')) {
                $table->foreignId('cancelled_by')->nullable()->after('cancelled_at')->constrained('users')->nullOnDelete();
            }

            $table->index('service_date', 'orders_service_date_idx');
            $table->index(['service_date', 'status', 'is_paid'], 'orders_service_status_paid_idx');
        });

        Schema::table('dishes', function (Blueprint $table) {
            $table->index('service_date', 'dishes_service_date_idx');
            $table->index(['service_date', 'is_active'], 'dishes_service_active_idx');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_service_status_paid_idx');
            $table->dropIndex('orders_service_date_idx');

            if (Schema::hasColumn('orders', 'cancelled_by')) {
                $table->dropConstrainedForeignId('cancelled_by');
            }

            if (Schema::hasColumn('orders', 'cancelled_at')) {
                $table->dropColumn('cancelled_at');
            }

            if (Schema::hasColumn('orders', 'status_updated_by')) {
                $table->dropConstrainedForeignId('status_updated_by');
            }

            if (Schema::hasColumn('orders', 'status_updated_at')) {
                $table->dropColumn('status_updated_at');
            }

            if (Schema::hasColumn('orders', 'paid_by')) {
                $table->dropConstrainedForeignId('paid_by');
            }

            if (Schema::hasColumn('orders', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
        });

        Schema::table('dishes', function (Blueprint $table) {
            $table->dropIndex('dishes_service_active_idx');
            $table->dropIndex('dishes_service_date_idx');
        });
    }
};
