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
        Schema::table('cart_items', function (Blueprint $table) {
            if (!Schema::hasColumn('cart_items', 'package_name')) {
                $table->longText('package_name')->nullable();
            }
            if (!Schema::hasColumn('cart_items', 'package_qty')) {
                $table->unsignedBigInteger('package_qty')->nullable();
            }
            if (!Schema::hasColumn('cart_items', 'package_price')) {
                $table->unsignedBigInteger('package_price')->nullable();
            }
            if (!Schema::hasColumn('cart_items', 'package_total')) {
                $table->float('package_total')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn('package_total');
            $table->dropColumn('package_name');
            $table->dropColumn('package_price');
            $table->dropColumn('package_qty');
        });
    }
};
