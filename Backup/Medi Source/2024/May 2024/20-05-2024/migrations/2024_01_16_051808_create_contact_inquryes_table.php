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
        Schema::create('contact_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('user_type');
            $table->string('clinical_difference');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('clinic_name');
            $table->string('website');
            $table->integer('number_of_physicians')->nullable();
            $table->integer('number_of_locations')->nullable();
            $table->integer('license_number')->nullable();
            $table->integer('dea_number')->nullable();
            $table->string('products_services_interested');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_inquiries');
    }
};
