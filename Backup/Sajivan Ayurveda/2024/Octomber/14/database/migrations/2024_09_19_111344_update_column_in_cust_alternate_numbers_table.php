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
        Schema::table('cust_alternate_numbers', function (Blueprint $table) {
            $table->tinyInteger('alt_wa_exist')->nullable()->change();
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->tinyInteger('wa_exist')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cust_alternate_numbers', function (Blueprint $table) {
            $table->tinyInteger('alt_wa_exist')->nullable()->change();
        });
    }
};
