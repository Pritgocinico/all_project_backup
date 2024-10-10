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
        Schema::dropIfExists('travel_leads');
        Schema::create('travel_leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->string('travel_inquiry_type')->nullable();
            $table->string('travel_inquiry_date')->nullable();
            $table->string('flight_form')->nullable();
            $table->date('inquiry_date')->nullable();
            $table->unsignedBigInteger('no_of_passengers')->nullable();
            $table->string('travel_sector')->nullable();
            $table->string('travel_mode')->nullable();
            $table->string('all_passengers_are_traveling_back')->nullable();
            $table->string('passenger_travel_other_sector')->nullable();
            $table->string('booking_status')->nullable();
            $table->string('aadhar_card_number_travel')->nullable();
            $table->string('passport_number_travel')->nullable();
            $table->string('followup_date')->nullable();
            $table->string('pending_remarks')->nullable();
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
