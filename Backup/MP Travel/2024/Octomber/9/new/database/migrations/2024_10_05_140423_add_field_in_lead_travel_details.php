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
        Schema::table('lead_travel_details', function (Blueprint $table) {
            if(!Schema::hasColumn('lead_travel_details','doc_file')) {
                $table->longText('doc_file')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_travel_details', function (Blueprint $table) {
            //
        });
    }
};
