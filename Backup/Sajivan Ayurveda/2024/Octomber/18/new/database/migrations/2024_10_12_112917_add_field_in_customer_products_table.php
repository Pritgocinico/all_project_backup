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
        Schema::table('customer_products', function (Blueprint $table) {
            if(!Schema::hasColumn('customer_products','quantity')) {
                $table->unsignedBigInteger('quantity')->default(0)->nullable();
            }
            if(!Schema::hasColumn('customer_products','amount')) {
                $table->unsignedBigInteger('amount')->default(0)->nullable();
            }
            if(!Schema::hasColumn('customer_products','tax')) {
                $table->unsignedBigInteger('tax')->default(0)->nullable();
            }
            if(!Schema::hasColumn('customer_products','invoice')) {
                $table->string('invoice')->nullable();
            }
        });
        Schema::table('leads', function (Blueprint $table) {
            if(!Schema::hasColumn('leads','total_amount')) {
                $table->unsignedBigInteger('total_amount')->default(0)->nullable();
            }
            if(!Schema::hasColumn('leads','collectable_amount')) {
                $table->unsignedBigInteger('collectable_amount')->default(0)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_products', function (Blueprint $table) {
            //
        });
    }
};
