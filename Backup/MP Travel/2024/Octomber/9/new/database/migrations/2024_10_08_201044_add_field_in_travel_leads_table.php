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
            if(!Schema::hasColumn('travel_leads','tcs_percentage')) {
                $table->unsignedBigInteger('tcs_percentage')->nullable();
            }
            if(!Schema::hasColumn('travel_leads','tcs_declaration_form')) {
                $table->string('tcs_declaration_form')->nullable();
            }
            if(Schema::hasColumn('travel_leads','eligible_tcs_amount')) {
                $table->string('eligible_tcs_amount')->nullable()->change();
            }
        });
        Schema::table('customers', function (Blueprint $table) {
            if(!Schema::hasColumn('customers','name_title')) {
                $table->string('name_title')->nullable();
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
