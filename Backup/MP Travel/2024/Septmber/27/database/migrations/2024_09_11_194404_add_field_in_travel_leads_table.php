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
            if(!Schema::hasColumn('travel_leads','travel_mode')){
                $table->string('travel_mode')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','tcs_amount')){
                $table->unsignedBigInteger('tcs_amount')->nullable();
            }
        });
        Schema::table('lead_travel_details', function (Blueprint $table) {
            if(!Schema::hasColumn('lead_travel_details','dob')){
                $table->date('dob')->nullable();
            }
            if(!Schema::hasColumn('lead_travel_details','passport_number')){
                $table->string('passport_number')->nullable();
            }
            if(!Schema::hasColumn('lead_travel_details','passport_file')){
                $table->string('passport_file')->nullable();
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
