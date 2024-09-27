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
        Schema::create('parameters', function (Blueprint $table) {
            $table->id();
            $table->integer('sub_category_id')->nullable();
            $table->integer('type')->nullable();
            $table->string('carrier_type')->nullable();
            $table->string('carrier')->nullable();
            $table->double('carrier_value')->default(0)->nullable();
            $table->string('label')->nullable();
            $table->string('taxi_param_label')->nullable();
            $table->double('taxi_value')->default(0)->nullable();
            $table->string('taxi_cc')->nullable();
            $table->double('taxi_cc_value')->default(0)->nullable();
            $table->double('seating_capacity_rate')->default(0)->nullable();
            $table->string('display_type')->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parameters');
    }
};
