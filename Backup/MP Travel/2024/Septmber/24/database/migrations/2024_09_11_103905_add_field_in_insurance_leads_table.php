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
            if(!Schema::hasColumn('insurance_leads','same_address')){
                $table->unsignedBigInteger('same_address')->after('lead_id')->nullable();
            }
            if(!Schema::hasColumn('insurance_leads','attendant_charge')){
                $table->unsignedBigInteger('attendant_charge')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insurance_leads', function (Blueprint $table) {
            if (Schema::hasColumn('insurance_leads', 'same_address')) {
                $table->dropColumn('same_address');
            }
        });
    }
};
