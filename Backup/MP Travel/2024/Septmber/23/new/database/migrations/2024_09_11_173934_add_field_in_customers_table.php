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
        Schema::table('customers', function (Blueprint $table) {
            if(!Schema::hasColumn('customers','customer_type')){
                $table->string('customer_type')->nullable();
            }
            if(!Schema::hasColumn('customers','budget_amount')){
                $table->unsignedBigInteger('budget_amount')->nullable();
            }
            if(!Schema::hasColumn('customers','premium_amount')){
                $table->unsignedBigInteger('premium_amount')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
