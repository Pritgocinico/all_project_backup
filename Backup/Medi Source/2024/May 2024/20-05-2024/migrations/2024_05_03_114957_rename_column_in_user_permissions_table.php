<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_permissions', function (Blueprint $table) {
            if (Schema::hasColumn('user_permissions', 'role_id')) {
                $table->dropColumn('role_id');
            }
            if (!Schema::hasColumn('user_permissions', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
        });
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role_name')) {
                $table->string('role_name')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_permissions', function (Blueprint $table) {
            //
        });
    }
};
