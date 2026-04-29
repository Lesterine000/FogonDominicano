<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('google_id');
        });

        $now = now();
        $localAdminEmail = (string) config('admin.local_email', 'admin@fogondominicano.local');
        $localAdminUsername = (string) config('admin.local_username', 'admin');
        $localAdminPassword = (string) env('ADMIN_PASSWORD', '40233469812!');

        DB::table('users')->updateOrInsert(
            ['email' => $localAdminEmail],
            [
                'name' => $localAdminUsername,
                'email_verified_at' => $now,
                'password' => Hash::make($localAdminPassword),
                'is_admin' => true,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );
    }

    public function down(): void
    {
        $localAdminEmail = (string) config('admin.local_email', 'admin@fogondominicano.local');

        DB::table('users')->where('email', $localAdminEmail)->delete();

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
