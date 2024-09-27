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
        Schema::create('lead', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable();
            $table->unsignedBigInteger('phone_no')->nullable();
            $table->string('address')->nullable();
            $table->string('state')->nullable();
            $table->string('district')->nullable();
            $table->string('sub_district')->nullable();
            $table->string('village')->nullable();
            $table->unsignedBigInteger('pin_code')->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->enum('lead_status',['1','0'])->default(1)->comment('1=Active and 0=Inactive');
            $table->enum('status',['1','0'])->default(1)->comment('1=Active and 0=Inactive');
            $table->timestamp('lead_follow_date_time')->nullable();
            $table->longText('remarks')->nullable();
            // $table->string('lead_id')->nullable();
            $table->string('lead_id')->nullable();
            $table->double('amount',8,2)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead');
    }
};
