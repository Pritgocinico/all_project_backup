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
            if(!Schema::hasColumn('insurance_leads','previous_policy')){
                $table->string('previous_policy')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','sum_insurance')){
                $table->string('sum_insurance')->nullable();
            }
        });
        Schema::table('insurance_leads', function (Blueprint $table) {
            if(!Schema::hasColumn('insurance_leads','health_company_name')){
                $table->string('health_company_name')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insurance_leads', function (Blueprint $table) {
            if(Schema::hasColumn('insurance_leads','previous_policy')){
                $table->dropColumn('previous_policy');
            }
            if(Schema::hasColumn('insurance_leads','sum_insurance')){
                $table->dropColumn('sum_insurance');
            }
        });
    }
};
