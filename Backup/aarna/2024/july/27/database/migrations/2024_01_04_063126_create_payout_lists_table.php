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
        Schema::create('payout_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('agent_id')->default(0)->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->integer('status')->default(1)->nullable();
            $table->float('disbursement_amount')->nullable();
            $table->string('payment_type')->nullable();
            $table->text('comment')->nullable();
            $table->timestamp('disbursement_date')->nullable();
            $table->text('disbursement_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_lists');
    }
};
