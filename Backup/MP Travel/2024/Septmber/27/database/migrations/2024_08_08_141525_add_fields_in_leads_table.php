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
            if(!Schema::hasColumn('leads','health_policy_type')){
                $table->longText('health_policy_type')->nullable();
            }
            if(!Schema::hasColumn('leads','claim_history')){
                $table->longText('claim_history')->nullable();
            }
            if(!Schema::hasColumn('leads','number_of_prex')){
                $table->unsignedBigInteger('number_of_prex')->nullable();
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
