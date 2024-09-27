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
        Schema::create('travel_leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->date('travel_from')->nullable();
            $table->date('travel_to')->nullable();
            $table->unsignedBigInteger('no_of_day')->nullable();
            $table->string('no_of_pax')->nullable();
            $table->string('travel_destination')->nullable();
            $table->string('flight_preference')->nullable();
            $table->string('hotel_preference')->nullable();
            $table->longText('other_service')->nullable();
            $table->longText('remarks')->nullable();
            $table->longText('other_service_charge')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_leads');
    }
};
