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
        Schema::create('covernotes', function (Blueprint $table) {
            $table->id();
            $table->integer('insurance_type')->default(1)->nullable();
            $table->integer('business_source')->nullable();
            $table->integer('category')->nullable();
            $table->integer('sub_category')->nullable();
            $table->integer('policy_type')->nullable();
            $table->integer('policy_individual_rate')->nullable();
            $table->integer('health_plan')->nullable();
            $table->integer('health_category')->nullable();
            $table->integer('company')->nullable();
            $table->string('covernote_no')->nullable();
            $table->integer('customer')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_chassis_no')->nullable();
            $table->integer('cc')->nullable();
            $table->integer('paid_driver')->nullable();
            $table->integer('gcv_type')->nullable();
            $table->integer('pcv_type')->nullable();
            $table->integer('nfpp')->nullable();
            $table->integer('worker')->nullable();
            $table->integer('public_carrier')->nullable();
            $table->integer('private_carrier')->nullable();
            $table->integer('idv_amount')->default(0)->nullable();
            $table->integer('net_premium_amount')->default(0)->nullable();
            $table->integer('business_amount')->default(0)->nullable();
            $table->text('pyp_no')->nullable();
            $table->text('pyp_insurance_company')->nullable();
            $table->timestamp('pyp_expiry_date')->nullable();
            $table->integer('agent')->default(0)->nullable();
            $table->string('vehicle_make')->nullable();
            $table->string('vehicle_registration_no')->nullable();
            $table->string('year_of_manufacture')->nullable();
            $table->integer('seating_capacity')->nullable();
            $table->integer('pa_to_passenger')->nullable();
            $table->integer('gross_premium_amount')->nullable();
            $table->integer('sum_insured_amount')->default(0)->nullable();
            $table->timestamp('risk_start_date')->nullable();
            $table->timestamp('risk_end_date')->nullable();
            $table->integer('payment_type')->nullable();
            $table->text('cheque_no')->nullable();
            $table->timestamp('cheque_date')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('transaction_no')->nullable();
            $table->string('policy_no')->nullable();
            $table->integer('team_lead')->default('0')->nullable();
            $table->string('policy_document')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('created_by')->default(0)->nullable();
            $table->integer('status')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('covernotes');
    }
};
