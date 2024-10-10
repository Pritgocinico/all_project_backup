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
            if(!Schema::hasColumn('travel_leads','travel_destination')) {
                $table->string('travel_destination')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','flexible_dates')) {
                $table->string('flexible_dates')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','fixed_dates')) {
                $table->string('fixed_dates')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','specific_place_interest')) {
                $table->string('specific_place_interest')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','travel_type')) {
                $table->string('travel_type')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','hotel_category')) {
                $table->string('hotel_category')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','meal_plan_preference')) {
                $table->string('meal_plan_preference')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','transport_category')) {
                $table->string('transport_category')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','domestic_other_services')) {
                $table->string('domestic_other_services')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','area_stay')) {
                $table->string('area_stay')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','pickup_date')) {
                $table->date('pickup_date')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','drop_date')) {
                $table->date('drop_date')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','self_drive')) {
                $table->string('self_drive')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','vehicle_chauffer')) {
                $table->string('vehicle_chauffer')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','vehicle_type')) {
                $table->string('vehicle_type')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','specific_requirement')) {
                $table->string('specific_requirement')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','visa_travel_date')) {
                $table->string('visa_travel_date')->nullable();
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
