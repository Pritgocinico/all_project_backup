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
        Schema::table('follow_up_checklist_items', function (Blueprint $table) {
            if(!Schema::hasColumn('follow_up_checklist_items','task_status')){
                $table->unsignedBigInteger('task_status')->default(0)->comment('0= In Progress and 1 = Complete');
            }
            if(!Schema::hasColumn('follow_up_checklist_items','complete_date')){
                $table->dateTime('complete_date')->nullable();
            }
        });
        Schema::table('follow_up_events', function (Blueprint $table) {
            if(!Schema::hasColumn('follow_up_events','priority')){
                $table->unsignedBigInteger('priority')->nullable();
            }
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('follow_up_checklist_items', function (Blueprint $table) {
            if(Schema::hasColumn('follow_up_checklist_items','task_status')){
                $table->dropColumn('task_status');
            }
            if(Schema::hasColumn('follow_up_checklist_items','complete_date')){
                $table->dropColumn('complete_date');
            }
        });
        Schema::table('follow_up_events', function (Blueprint $table) {
            if(Schema::hasColumn('follow_up_events','priority')){
                $table->dropColumn('priority');
            }
        });
    }
};
