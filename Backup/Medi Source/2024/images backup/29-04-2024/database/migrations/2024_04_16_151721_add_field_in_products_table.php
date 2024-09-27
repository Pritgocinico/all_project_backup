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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'keyword')) {
                $table->string('keyword')->nullable();
            }
            if (!Schema::hasColumn('products', 'stock')) {
                $table->unsignedBigInteger('stock')->nullable();
            }
            if (!Schema::hasColumn('products', 'description')) {
                $table->longText('description')->nullable();
            }
            if (!Schema::hasColumn('products', 'vial_weight')) {
                $table->string('vial_weight')->nullable();
            }
            if (!Schema::hasColumn('products', 'medical_necessity')) {
                $table->longText('medical_necessity')->nullable();
            }
            if (!Schema::hasColumn('products', 'preservative_free')) {
                $table->string('preservative_free')->nullable();
            }
            if (!Schema::hasColumn('products', 'sterile_type')) {
                $table->string('sterile_type')->nullable();
            }
            if (!Schema::hasColumn('products', 'controlled_state')) {
                $table->string('controlled_state')->nullable();
            }
            if (!Schema::hasColumn('products', 'cold_ship')) {
                $table->string('cold_ship')->nullable();
            }
            if (!Schema::hasColumn('products', 'max_order_qty')) {
                $table->unsignedBigInteger('max_order_qty')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
