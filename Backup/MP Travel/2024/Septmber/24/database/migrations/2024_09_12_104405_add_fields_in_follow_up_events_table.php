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
        Schema::table('follow_up_events', function (Blueprint $table) {
            if(!Schema::hasColumn('follow_up_events','follow_up_code')){
                $table->string('follow_up_code')->nullable();
            }
        });
        Schema::table('follow_up_checklist_items', function (Blueprint $table) {
            if(!Schema::hasColumn('follow_up_checklist_items','sub_follow_up_code')){
                $table->string('sub_follow_up_code')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('follow_up_events', function (Blueprint $table) {
            if(Schema::hasColumn('follow_up_events','follow_up_code')){
                $table->dropColumn('follow_up_code');
            }
        });
        Schema::table('follow_up_checklist_items', function (Blueprint $table) {
            if(Schema::hasColumn('follow_up_checklist_items','sub_follow_up_code')){
                $table->dropColumn('sub_follow_up_code');
            }
        });
    }
};
