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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable();
            $table->string('phoneno')->nullable();
            $table->text('address')->nullable();
            $table->string('state')->nullable();
            $table->string('district')->nullable();
            $table->string('sub_district')->nullable();
            $table->string('village')->nullable();
            $table->string('pincode')->nullable();
            $table->date('excepted_delievery_date')->nullable();
            $table->enum('order_lead_status',[1,0])->comment('1=Active and 0= InActive')->default(0);
            $table->datetime('lead_followup_datetime')->nullable();
            $table->enum('divert_order_status',[1,0])->comment('1=Active and 0= InActive')->default(0);
            $table->string('staff')->nullable();
            $table->text('divert_note')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
