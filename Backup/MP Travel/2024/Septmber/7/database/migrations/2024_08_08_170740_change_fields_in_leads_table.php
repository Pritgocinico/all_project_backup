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
        Schema::table('leads', function (Blueprint $table) {
            if(Schema::hasColumn('leads','old_policy_copy_attachment')){
                $table->json('old_policy_copy_attachment')->nullable()->change();
            }
            if(Schema::hasColumn('leads','previous_copy_attachment')){
                $table->json('previous_copy_attachment')->nullable()->change();
            }
            if(Schema::hasColumn('leads','claim_history')){
                $table->json('claim_history')->nullable()->change();
            }
            if(Schema::hasColumn('leads','sum_insurance')){
                $table->unsignedBigInteger('sum_insurance')->nullable()->change();
            }
            if(!Schema::hasColumn('leads','previous_policy')){
                $table->string('previous_policy')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            //
        });
    }
};
