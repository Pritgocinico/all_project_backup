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
        Schema::table('businesses', function (Blueprint $table) {
            if (!Schema::hasColumn('businesses', 'transaction_id')) {
                $table->longText('transaction_id')->nullable();
            }
            if (!Schema::hasColumn('businesses', 'payment_date_time')) {
                $table->dateTime('payment_date_time')->nullable();
            }
            if (!Schema::hasColumn('businesses', 'linked_url')) {
                $table->string('linked_url')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business', function (Blueprint $table) {
            $table->dropColumn('transaction_id');
            $table->dropColumn('payment_date_time');
            $table->dropColumn('linked_url');
        });
    }
};
