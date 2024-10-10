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
        Schema::table('insurance_leads', function (Blueprint $table) {
            if(!Schema::hasColumn('insurance_leads','insurance_type')){
                $table->string('insurance_type')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','pa_company_name')){
                $table->string('pa_company_name')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','occupation')){
                $table->string('occupation')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','policy_insurer_name')){
                $table->string('policy_insurer_name')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','permanent_disability')){
                $table->string('permanent_disability')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','fracture_care')){
                $table->string('fracture_care')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','hospital_cash_benefit')){
                $table->string('hospital_cash_benefit')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','accidental_hospitalization_expenses')){
                $table->string('accidental_hospitalization_expenses')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','adventure_sports_benefit')){
                $table->string('adventure_sports_benefit')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','policy_period')){
                $table->string('policy_period')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','distance_near_hospital')){
                $table->string('distance_near_hospital')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','first_aid_kit')){
                $table->string('first_aid_kit')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','fire_extinguishers')){
                $table->string('fire_extinguishers')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','wc_security_person')){
                $table->string('wc_security_person')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','policy_number')){
                $table->string('policy_number')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','expiry_date')){
                $table->date('expiry_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insurance_leads', function (Blueprint $table) {
            //
        });
    }
};
