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
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'phone_code')) {
                $table->unsignedBigInteger('phone_code')->nullable();
            }
            if (!Schema::hasColumn('customers', 'age')) {
                $table->unsignedBigInteger('age')->nullable();
            }
        });
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'hold_complete_date_time')) {
                $table->dateTime('hold_complete_date_time')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
