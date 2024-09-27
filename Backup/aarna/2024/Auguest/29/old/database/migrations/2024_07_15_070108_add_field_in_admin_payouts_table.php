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
        Schema::table('admin_payouts', function (Blueprint $table) {
            if(!Schema::hasColumn('admin_payouts','payout_on')) {
                $table->string('payout_on')->nullable();
            }
            if(!Schema::hasColumn('admin_payouts','deleted_at')) {
                $table->softDeletes();
            }
        });
        Schema::table('sourcing_agent_payouts', function (Blueprint $table) {
            if(!Schema::hasColumn('sourcing_agent_payouts','payout_on')) {
                $table->string('payout_on')->nullable();
            }
            if(!Schema::hasColumn('sourcing_agent_payouts','deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_payouts', function (Blueprint $table) {
            Schema::dropIfExists('payout_on');
            Schema::dropIfExists('deleted_at');
        });
        Schema::table('sourcing_agent_payouts', function (Blueprint $table) {
            Schema::dropIfExists('payout_on');
            Schema::dropIfExists('deleted_at');
        });
    }
};
