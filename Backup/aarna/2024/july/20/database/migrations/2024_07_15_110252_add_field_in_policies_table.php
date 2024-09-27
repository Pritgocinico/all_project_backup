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
        Schema::table('policies', function (Blueprint $table) {
            if(!Schema::hasColumn('policies','payout_restricted')){
                $table->string('payout_restricted')->nullable();
            }
            if(!Schema::hasColumn('policies','payout_restricted_remark')){
                $table->string('payout_restricted_remark')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policies', function (Blueprint $table) {
            if(Schema::hasColumn('policies','payout_restricted')){
                $table->dropColumn('payout_restricted');
            }
            if(Schema::hasColumn('policies','payout_restricted_remark')){
                $table->dropColumn('payout_restricted_remark');
            }
        });
    }
};
