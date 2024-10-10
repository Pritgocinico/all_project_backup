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
            if(!Schema::hasColumn('travel_leads','doc_check_list_id')) {
                $table->unsignedBigInteger('doc_check_list_id')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','document_country')) {
                $table->string('document_country')->nullable();
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
