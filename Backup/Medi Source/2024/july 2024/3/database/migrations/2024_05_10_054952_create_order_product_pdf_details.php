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
        if (!Schema::hasTable('order_product_pdf_details')) {
            Schema::create('order_product_pdf_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_pdf_detail_id')->nullable();
                $table->unsignedBigInteger('order_id')->nullable();
                $table->unsignedBigInteger('order_item_id')->nullable();
                $table->unsignedBigInteger('product_id')->nullable();
                $table->string('package_name')->nullable();
                $table->string('lot_number')->nullable();
                $table->string('lot')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product_pdf_details');
    }
};
