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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->string('productname');
            $table->string('inactive_ingredients');
            $table->string('unit_size_type');
            $table->string('package_size');
            $table->string('product_code');
            $table->string('ndc');
            $table->string('storage');
            $table->decimal('price', 10, 2)->nullable(); // Add this line for the 'price' column
         
            $table->string('single_image')->nullable(); // Add this line
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
