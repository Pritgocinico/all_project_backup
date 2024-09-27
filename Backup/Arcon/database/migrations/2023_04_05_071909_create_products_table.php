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
            $table->text('name')->nullable();
            $table->string('sku')->nullable();
            $table->string('image')->nullable();
            $table->string('slider')->nullable();
            $table->integer('category')->nullable();
            $table->integer('brand_name')->nullable();
            $table->text('description')->nullable();
            $table->text('cgst')->nullable();
            $table->text('sgst')->nullable();
            $table->integer('status')->default('0');
            $table->integer('active')->default('0');
            $table->integer('created_by')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
