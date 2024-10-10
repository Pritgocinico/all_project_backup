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
            if(!Schema::hasColumn('travel_leads','duration_of_stay')) {
                $table->string('duration_of_stay')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','number_of_customers')) {
                $table->string('number_of_customers')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','travel_country')) {
                $table->string('travel_country')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','purpose_of_travel')) {
                $table->string('purpose_of_travel')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','visa_type')) {
                $table->string('visa_type')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','expense_bearer')) {
                $table->string('expense_bearer')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','first_time_traveler')) {
                $table->string('first_time_traveler')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','travel_history')) {
                $table->string('travel_history')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','visa_rejection')) {
                $table->string('visa_rejection')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','travel_other_services')) {
                $table->string('travel_other_services')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','visa_rejection_country')) {
                $table->string('visa_rejection_country')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','visa_rejection_reason')) {
                $table->string('visa_rejection_reason')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','other_service_document')) {
                $table->longText('other_service_document')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','travel_form_date`')) {
                $table->date('travel_form_date')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','travel_to_date')) {
                $table->string('travel_to_date')->nullable();
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
