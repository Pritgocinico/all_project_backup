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
        Schema::create('payout_list_records', function (Blueprint $table) {
            $table->id();
            $table->integer('payout_list_id')->nullable();
            $table->integer('policy_id')->nullable();
            $table->string('policy_no')->nullable();
            $table->datetime('policy_date')->nullable();
            $table->integer('customer')->default('0');
            $table->float('net_premium')->default('0');
            $table->float('od')->default('0');
            $table->float('tp')->default('0');
            $table->float('percentage')->default('0');
            $table->float('payout')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_list_records');
    }
};
