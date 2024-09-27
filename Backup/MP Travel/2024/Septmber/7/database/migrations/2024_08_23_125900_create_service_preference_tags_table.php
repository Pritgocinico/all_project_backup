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
        Schema::create('service_preference_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_preference_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('service_preference_id')->references('id')->on('service_preferences')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_preference_tags');
    }
};
