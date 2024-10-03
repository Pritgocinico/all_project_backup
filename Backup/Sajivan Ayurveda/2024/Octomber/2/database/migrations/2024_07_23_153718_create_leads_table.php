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
            $table->string('mf_new_existing')->nullable();
            $table->string('fd_new_existing')->nullable();
            $table->string('bond_new_existing')->nullable();
            $table->string('mf_product_name')->nullable();
            $table->string('fd_product_name')->nullable();
            $table->string('bond_product_name')->nullable();
            $table->string('mf_sip_lumsum')->nullable();
            $table->decimal('mf_sip_amount', 10, 2)->nullable();
            $table->decimal('fd_amount_of_investment', 10, 2)->nullable();
            $table->decimal('bond_amount_of_investment', 10, 2)->nullable();
            $table->date('mf_investment_date')->nullable();
            $table->date('fd_investment_date')->nullable();
            $table->date('bond_investment_date')->nullable();
            $table->date('mf_sip_start_date')->nullable();
            $table->integer('mf_no_of_installments')->nullable();
            $table->date('fd_maturity_date')->nullable();
            $table->date('bond_maturity_date')->nullable();
            $table->decimal('fd_rate_of_interest', 5, 2)->nullable();
            $table->decimal('bond_rate_of_interest', 5, 2)->nullable();
            $table->string('lead_managed_by')->nullable();
            $table->date('lead_date')->nullable();
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
