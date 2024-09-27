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
            if(!Schema::hasColumn('insurance_leads','exist_diseases')){
                $table->longText('exist_diseases')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','nine_month_period')){
                $table->longText('nine_month_period')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','one_year_waiting')){
                $table->longText('one_year_waiting')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','room_rent_capping')){
                $table->longText('room_rent_capping')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','maternity_benefit')){
                $table->longText('maternity_benefit')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','pre_post_hospital')){
                $table->longText('pre_post_hospital')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','ambulance_charge')){
                $table->longText('ambulance_charge')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','day_care_procedures')){
                $table->longText('day_care_procedures')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','terrorism')){
                $table->longText('terrorism')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','organ_donor')){
                $table->longText('organ_donor')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','internal_external_disease')){
                $table->longText('internal_external_disease')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','lucentis')){
                $table->longText('lucentis')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','reasonable_charge')){
                $table->longText('reasonable_charge')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','dental_treatment_accident')){
                $table->longText('dental_treatment_accident')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','domiciliary_hospital')){
                $table->longText('domiciliary_hospital')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','modern_treatment')){
                $table->longText('modern_treatment')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','ayush_treatment')){
                $table->longText('ayush_treatment')->nullable();
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
