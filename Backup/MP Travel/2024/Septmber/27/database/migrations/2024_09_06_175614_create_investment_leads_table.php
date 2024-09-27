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
        Schema::create('investment_leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->string('investment_type')->nullable();
            $table->string('investment_field')->nullable();
            $table->string('investment_code')->nullable();
            $table->longText('investment_remark')->nullable();
            $table->string('kyc_status')->nullable();
            $table->string('product_name')->nullable();
            $table->string('invest_amount')->nullable();
            $table->date('investment_date')->nullable();
            $table->longText('cancel_cheque')->nullable();
            $table->string('sip_amount')->nullable();
            $table->string('sip_date')->nullable();
            $table->string('lumsum_amount')->nullable();
            $table->string('rate_of_interest')->nullable();
            $table->string('investment_mode')->nullable();
            $table->string('tenure')->nullable();
            $table->string('maturity_date')->nullable();
            $table->string('maturity_amount')->nullable();
            $table->string('investment_payout')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_leads');
    }
};
