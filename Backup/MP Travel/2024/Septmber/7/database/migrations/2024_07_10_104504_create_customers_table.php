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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->nullable();
            $table->string('name')->nullable();
            $table->string('short_name')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('mobile_number')->nullable();
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('pan_card_number')->nullable();
            $table->longText('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('pin_code')->nullable();
            $table->unsignedBigInteger('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->unsignedBigInteger('card_number')->nullable();
            $table->string('card_name')->nullable();
            $table->string('card_type')->nullable();
            $table->unsignedBigInteger('card_month')->nullable();
            $table->unsignedBigInteger('card_year')->nullable();
            $table->unsignedBigInteger('card_cvv')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
