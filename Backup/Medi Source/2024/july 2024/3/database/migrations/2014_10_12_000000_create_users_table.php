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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('organization_name')->nullable();
            $table->string('name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('profile_image')->nullable();
            $table->string('mobile')->nullable();
            $table->string('address')->nullable();
            $table->unsignedBigInteger('role')->default(0);
            $table->string('status')->default('inactive');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('password1')->nullable();
            $table->string('npi')->nullable();
            $table->string('business_license_number')->nullable();
            $table->string('prescriber_state_license_number')->nullable();
            $table->string('dea_number')->nullable();
            $table->string('speciality')->nullable();
            $table->string('practice_address_street')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->boolean('consent')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
