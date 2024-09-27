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
        Schema::table('batch', function (Blueprint $table) {
            if (!Schema::hasColumn('batch', 'car_no')) {
                $table->string('car_no')->nullable();
            }
            if (!Schema::hasColumn('batch', 'car_name')) {
                $table->string('car_name')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batch', function (Blueprint $table) {
            //
        });
    }
};
