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
            $table->string('cust_age')->nullable();
            $table->string('cust_height')->nullable();
            $table->string('cust_weight')->nullable();
            $table->string('cust_disease')->nullable();
            $table->tinyInteger('wa_exist')->default(1)->comment('1=yes and 0= no');
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
