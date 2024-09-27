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
        Schema::create('lead_travel_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('child_type')->nullable();
            $table->string('child_name')->nullable();
            $table->string('child_age')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_travel_details');
    }
};
