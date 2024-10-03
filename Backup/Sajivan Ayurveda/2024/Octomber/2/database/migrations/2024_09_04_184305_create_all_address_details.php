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
        Schema::create('all_address_details', function (Blueprint $table) {
            $table->id();
            $table->longText('village_location')->nullable();
            $table->longText('office_name')->nullable();
            $table->unsignedBigInteger('pin_code')->nullable();
            $table->longText('sub_district_name')->nullable();
            $table->longText('district_name')->nullable();
            $table->longText('state_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('all_address_details');
    }
};
