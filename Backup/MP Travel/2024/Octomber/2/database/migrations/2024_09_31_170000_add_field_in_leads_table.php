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
        Schema::table('leads', function (Blueprint $table) {
            if(!Schema::hasColumn('leads', 'lead_pending_date_time')) {
                $table->dateTime('lead_pending_date_time')->nullable();
            } 
            if(!Schema::hasColumn('leads', 'lead_in_process_date_time')) {
                $table->dateTime('lead_in_process_date_time')->nullable();
            } 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            //
        });
    }
};
