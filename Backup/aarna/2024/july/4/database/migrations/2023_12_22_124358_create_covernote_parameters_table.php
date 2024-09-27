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
        Schema::create('covernote_parameters', function (Blueprint $table) {
            $table->id();
            $table->integer('covernote_id')->nullable();
            $table->integer('sub_category_id')->nullable();
            $table->integer('parameter_id')->nullable();
            $table->integer('type')->nullable();
            $table->string('label')->nullable();
            $table->double('taxi_value')->default(0)->nullable();
            $table->string('taxi_cc')->nullable();
            $table->double('taxi_cc_value')->default(0)->nullable();
            $table->double('seating_capacity')->default(0)->nullable();
            $table->double('paid_driver')->default(0)->nullable();
            $table->string('value')->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('covernote_parameters');
    }
};
