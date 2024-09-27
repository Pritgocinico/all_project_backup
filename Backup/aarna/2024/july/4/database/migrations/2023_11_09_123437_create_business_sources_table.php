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
        Schema::create('business_sources', function (Blueprint $table) {
            $table->id();
            $table->integer('insurance_type')->default(0)->nullable();
            $table->integer('category')->default(0)->nullable();
            $table->integer('business_type')->default(0)->nullable();
            $table->integer('customer')->default(0)->nullable();
            $table->integer('sub_category')->default(0)->nullable();
            $table->integer('company')->default(0)->nullable();
            $table->timestamp('risk_start_date')->nullable();
            $table->string('vehicle_chassic_no')->nullable();
            $table->float('gross_premium_amount')->default(0)->nullable();
            $table->float('net_premium_amount')->default(0)->nullable();
            $table->integer('health_plan')->default(0)->nullable();
            $table->integer('status')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_sources');
    }
};
