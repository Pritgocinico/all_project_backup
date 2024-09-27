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
            $table->string('sme_insurance')->nullable();
            $table->string('insurance_cover')->nullable();
            $table->string('insurance_value')->nullable();
            $table->string('hypothication')->nullable();
            $table->string('nature_of_business')->nullable();
            $table->string('fire_claim_history')->nullable();
            $table->string('good_description')->nullable();
            $table->string('invoice_copy')->nullable();
            $table->string('workers_number')->nullable();
            $table->string('salary_range')->nullable();
            $table->string('designation')->nullable();
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
