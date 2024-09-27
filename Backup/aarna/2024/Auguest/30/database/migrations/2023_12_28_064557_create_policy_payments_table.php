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
        Schema::create('policy_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('policy_id')->default(0)->nullable();
            $table->integer('payment_type')->default(1)->nullable();
            $table->text('cheque_no')->nullable();
            $table->timestamp('cheque_date')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('transaction_no')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->integer('status')->default(1)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_payments');
    }
};
