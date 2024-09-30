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
            if(!Schema::hasColumn('insurance_leads','business_type')){
                $table->string('business_type')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','permanent_total_disability')){
                $table->string('permanent_total_disability')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','permanent_partial_disability')){
                $table->string('permanent_partial_disability')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','accidental_hospital_cover')){
                $table->longText('accidental_hospital_cover')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','cashless_facility_hospital')){
                $table->longText('cashless_facility_hospital')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','burn_expense')){
                $table->longText('burn_expense')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','broken_bone_cover')){
                $table->longText('broken_bone_cover')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','report_mortal_remain')){
                $table->longText('report_mortal_remain')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','carriage_dead_body')){
                $table->longText('carriage_dead_body')->nullable();
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
