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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_id');
            $table->string('invest_type')->nullable();
            $table->string('insurance_type')->nullable();
            $table->string('product_name')->nullable();
            $table->decimal('amount_of_investment', 10, 2)->nullable();
            $table->date('investment_date')->nullable();
            $table->string('sip')->nullable();
            $table->decimal('lumsum_amount', 10, 2)->nullable();
            $table->decimal('sip_amount', 10, 2)->nullable();
            $table->date('sip_date')->nullable();
            $table->integer('installment_no')->nullable();
            $table->decimal('interest_rate', 10, 2)->nullable();
            $table->date('maturity_date')->nullable();
            $table->string('managed_by')->nullable();
            $table->date('lead_date')->nullable();
            $table->string('insurer')->nullable();
            $table->string('insured')->nullable();
            $table->bigInteger('product')->nullable();
            $table->bigInteger('sub_product')->nullable();
            $table->date('received_date')->nullable();
            $table->decimal('sum_insurance', 5, 2)->nullable();
            $table->date('insurer_dob')->nullable();
            $table->string('vehicle')->nullable();
            $table->bigInteger('client')->nullable();
            $table->string('vehicle_make')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->bigInteger('assignee')->nullable();
            $table->string('fire_burglary')->nullable();
            $table->string('marine')->nullable();
            $table->string('wc')->nullable();
            $table->string('gmc')->nullable();
            $table->string('gpa')->nullable();
            $table->string('other_insurance')->nullable();
            $table->bigInteger('client_name')->nullable();
            $table->date('travel_from_date')->nullable();
            $table->date('travel_to_date')->nullable();
            $table->integer('number_of_days')->nullable();
            $table->integer('travel_destination')->nullable();
            $table->string('flight_preference')->nullable();
            $table->string('other_services')->nullable();
            $table->string('itinerary_flow')->nullable();
            $table->bigInteger('assigned_to')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
