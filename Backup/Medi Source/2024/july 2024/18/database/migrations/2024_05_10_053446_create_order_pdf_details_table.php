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
        if (!Schema::hasTable('order_pdf_details')) {
            Schema::create('order_pdf_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id')->nullable();
                $table->string('p_o_number')->nullable();
                $table->string('terms')->nullable();
                $table->string('rep')->nullable();
                $table->string('account_number')->nullable();
                $table->string('requested_ship')->nullable();
                $table->string('ship_via')->nullable();
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
        Schema::dropIfExists('order_pdf_details');
    }
};
