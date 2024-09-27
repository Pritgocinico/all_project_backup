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
        Schema::table('users', function (Blueprint $table) {
            if(!Schema::hasColumn('users', 'basic_amount')) {
                $table->unsignedBigInteger('basic_amount')->nullable();
            }
            if(!Schema::hasColumn('users', 'hra_amount')) {
                $table->unsignedBigInteger('hra_amount')->nullable();
            }
            if(!Schema::hasColumn('users', 'allowance_amount')) {
                $table->unsignedBigInteger('allowance_amount')->nullable();
            }
            if(!Schema::hasColumn('users', 'petrol_amount')) {
                $table->unsignedBigInteger('petrol_amount')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['basic_amount', 'hra_amount', 'allowance_amount', 'petrol_amount']);
        });
    }
};
