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
            if (Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable()->change();
            }
            if (Schema::hasColumn('products', 'productname')) {
                $table->string('productname')->nullable()->change();
            }
            if (Schema::hasColumn('products', 'inactive_ingredients')) {
                $table->string('inactive_ingredients')->nullable()->change();
            }
            if (Schema::hasColumn('products', 'unit_size_type')) {
                $table->string('unit_size_type')->nullable()->change();
            }
            if (Schema::hasColumn('products', 'package_size')) {
                $table->string('package_size')->nullable()->change();
            }
            if (Schema::hasColumn('products', 'product_code')) {
                $table->string('product_code')->nullable()->change();
            }
            if (Schema::hasColumn('products', 'ndc')) {
                $table->string('ndc')->nullable()->change();
            }
            if (Schema::hasColumn('products', 'storage')) {
                $table->string('storage')->nullable()->change();
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
