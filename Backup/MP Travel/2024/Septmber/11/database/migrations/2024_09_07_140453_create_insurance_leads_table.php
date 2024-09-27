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
        Schema::create('insurance_leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->string('risk_location_address')->nullable();
            $table->string('risk_location_city')->nullable();
            $table->string('risk_location_state')->nullable();
            $table->string('risk_location_country')->nullable();
            $table->string('risk_location_pin_code')->nullable();
            $table->date('policy_start_date')->nullable();
            $table->date('policy_end_date')->nullable();
            $table->date('insurance_to')->nullable();
            $table->string('financier_interest_hypo')->nullable();
            $table->unsignedBigInteger('building_value')->nullable();
            $table->unsignedBigInteger('plant_machinery')->nullable();
            $table->unsignedBigInteger('total_stock_in_value')->nullable();
            $table->unsignedBigInteger('fff_other_ee')->nullable();
            $table->unsignedBigInteger('other_content')->nullable();
            $table->unsignedBigInteger('total_sum_insured')->nullable();
            $table->string('policy_type')->nullable();
            $table->string('operational_fire')->nullable();
            $table->string('maintain_electric')->nullable();
            $table->string('water_drainage')->nullable();
            $table->string('security_cctv')->nullable();
            $table->longText('three_year_claim_history')->nullable();
            $table->string('basement')->nullable();
            $table->string('use_basement')->nullable();
            $table->string('insured_premises')->nullable();
            $table->string('risk_locate')->nullable();
            $table->longText('age_of_building')->nullable();
            $table->string('nature_business')->nullable();
            $table->string('risk_occupancy')->nullable();
            $table->string('policy_period')->nullable();
            $table->string('sub_contractor')->nullable();
            $table->string('occupational_disease')->nullable();
            $table->unsignedBigInteger('total_employees')->nullable();
            $table->unsignedBigInteger('total_wages')->nullable();
            $table->unsignedBigInteger('skilled')->nullable();
            $table->unsignedBigInteger('un_skilled')->nullable();
            $table->string('commercial_travel')->nullable();
            $table->string('medical_extension')->nullable();
            $table->string('number_shift')->nullable();
            $table->string('first_aid_kit')->nullable();
            $table->string('first_extinguishers')->nullable();
            $table->string('type_case')->nullable();
            $table->string('plan_type')->nullable();
            $table->string('health_claim_history')->nullable();
            $table->string('health_claim_detail')->nullable();
            $table->string('alcohol_consumer')->nullable();
            $table->string('tobacco_consumer')->nullable();
            $table->string('smoking')->nullable();
            $table->string('ped_medical')->nullable();
            $table->string('CIR')->nullable();
            $table->string('monthly_salary')->nullable();
            $table->string('physical_disable')->nullable();
            $table->string('accident_coverage')->nullable();
            $table->string('loss_income_benefit')->nullable();
            $table->string('road_ambulance_cover')->nullable();
            $table->string('travel_expense_benefit')->nullable();
            $table->string('air_ambulance_cover')->nullable();
            $table->string('child_education_benefit')->nullable();
            $table->string('comma_due_accident')->nullable();
            $table->string('emi_payment_cover')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_leads');
    }
};
