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
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->string('transaction_number')->nullable();
                $table->string('membership')->nullable();
                $table->float('sub_total', 8, 2)->nullable();
                $table->float('tax_amount', 8, 2)->nullable();
                $table->float('tax_rate', 8, 4)->nullable();
                $table->string('status')->nullable();
                $table->string('gateway')->nullable();
                $table->string('subscription')->nullable();
                $table->timestamp('expiry_date')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
