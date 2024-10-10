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
            if(!Schema::hasColumn('travel_leads','tcs_amount')) {
                $table->unsignedBigInteger('tcs_amount')->nullable();
            }
            if(Schema::hasColumn('travel_leads','domestic_other_services')) {
                $table->unsignedBigInteger('domestic_other_services')->nullable()->change();
            }
            if(!Schema::hasColumn('travel_leads','domestic_other_services_remarks')) {
                $table->string('domestic_other_services_remarks')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('travel_leads', function (Blueprint $table) {
            if(Schema::hasColumn('travel_leads','tcs_amount')) {
                $table->dropColumn('tcs_amount');
            }
            if(!Schema::hasColumn('travel_leads','domestic_other_services')) {
                $table->string('domestic_other_services')->nullable()->change();
            }
            if(Schema::hasColumn('travel_leads','domestic_other_services_remarks')) {
                $table->dropColumn('domestic_other_services_remarks');
            }
        });
    }
};
