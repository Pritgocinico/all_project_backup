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
        Schema::table('travel_leads', function (Blueprint $table) {
            if (!Schema::hasColumn('travel_leads', 'flight_to')) {
                $table->string('flight_to')->nullable();
            }
            if (!Schema::hasColumn('travel_leads', 'departure_date')) {
                $table->string('departure_date')->nullable();
            }
            if (!Schema::hasColumn('travel_leads', 'domestic_fixed_date')) {
                $table->string('domestic_fixed_date')->nullable();
            }
            if (!Schema::hasColumn('travel_leads', 'flexible_month_year')) {
                $table->string('flexible_month_year')->nullable();
            }
            if (!Schema::hasColumn('travel_leads', 'number_of_day')) {
                $table->string('number_of_day')->nullable();
            }
            if (!Schema::hasColumn('travel_leads', 'domestic_week')) {
                $table->string('domestic_week')->nullable();
            }
            if (!Schema::hasColumn('travel_leads', 'aadhar_card_number_travel')) {
                $table->string('aadhar_card_number_travel')->nullable();
            }
            if (!Schema::hasColumn('travel_leads', 'passport_number_travel')) {
                $table->string('passport_number_travel')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_leads', function (Blueprint $table) {
            //
        });
    }
};
