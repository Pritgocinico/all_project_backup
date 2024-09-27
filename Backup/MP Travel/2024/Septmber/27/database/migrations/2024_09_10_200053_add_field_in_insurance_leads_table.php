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
            if(!Schema::hasColumn('insurance_leads','theft_extension')){
                $table->longText('theft_extension')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','first_loss_percentage')){
                $table->longText('first_loss_percentage')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','burglary_sum_insured')){
                $table->longText('burglary_sum_insured')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','burglary_coverage')){
                $table->longText('burglary_coverage')->nullable();
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
