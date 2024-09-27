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
            if (!Schema::hasColumn('businesses', 'plan_id')) {
                $table->unsignedBigInteger('plan_id')->nullable();
            }
            if (!Schema::hasColumn('businesses', 'payment_option')) {
                $table->string('payment_option')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('plan_id');
            $table->dropColumn('payment_option');
        });
    }
};
