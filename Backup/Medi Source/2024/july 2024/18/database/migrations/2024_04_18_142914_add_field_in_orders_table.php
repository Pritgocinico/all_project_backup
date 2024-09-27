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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'billing_address')) {
                $table->longText('billing_address')->nullable();
            }
            if (!Schema::hasColumn('orders', 'billing_address1')) {
                $table->longText('billing_address1')->nullable();
            }
            if (!Schema::hasColumn('orders', 'billing_city')) {
                $table->longText('billing_city')->nullable();
            }
            if (!Schema::hasColumn('orders', 'billing_state')) {
                $table->longText('billing_state')->nullable();
            }
            if (!Schema::hasColumn('orders', 'billing_zip_code')) {
                $table->longText('billing_zip_code')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
