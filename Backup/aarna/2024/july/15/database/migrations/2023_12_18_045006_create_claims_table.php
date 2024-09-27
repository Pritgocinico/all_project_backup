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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->integer('policy_id')->nullable();
            $table->string('claim_date')->nullable();
            $table->string('claim_no')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_person_no')->nullable();
            $table->string('surveyar_name')->nullable();
            $table->string('surveyar_no')->nullable();
            $table->string('surveyar_email')->nullable();
            $table->string('repairing_location')->nullable();
            $table->integer('claim_status')->nullable();
            $table->string('status_text')->nullable();
            $table->timestamp('status_date')->nullable();
            $table->integer('payment_type')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('cheque_date')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('transaction_no')->nullable();
            $table->text('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
