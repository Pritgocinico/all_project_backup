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
        if (!Schema::hasTable('coupon_details')) {
            Schema::create('coupon_details', function (Blueprint $table) {
                $table->id();
                $table->string('coupon_code')->unique()->nullable();
                $table->string('coupon_type')->nullable();
                $table->unsignedBigInteger('discount_percentage')->nullable();
                $table->unsignedBigInteger('product_id')->nullable();
                $table->unsignedBigInteger('quantity')->nullable();
                $table->unsignedBigInteger('discount_amount')->nullable();
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
        Schema::dropIfExists('coupon_code');
    }
};
