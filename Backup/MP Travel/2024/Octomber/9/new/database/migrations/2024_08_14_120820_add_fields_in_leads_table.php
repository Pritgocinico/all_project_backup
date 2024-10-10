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
            if(!Schema::hasColumn('leads','lead_in_process_remarks')){
                $table->longText('lead_in_process_remarks')->nullable();
            }
            if(!Schema::hasColumn('leads','lead_cancel_remarks')){
                $table->longText('lead_cancel_remarks')->nullable();
            }
            if(!Schema::hasColumn('leads','lead_complete_remarks')){
                $table->longText('lead_complete_remarks')->nullable();
            }
            if(!Schema::hasColumn('leads','lead_complete_date_time')){
                $table->dateTime('lead_complete_date_time')->nullable();
            }
            if(!Schema::hasColumn('leads','lead_cancel_date_time')){
                $table->dateTime('lead_cancel_date_time')->nullable();
            }
        });
        Schema::table('follow_up_events', function (Blueprint $table) {
            if(!Schema::hasColumn('follow_up_events','follow_up_in_process_remarks')){
                $table->longText('follow_up_in_process_remarks')->nullable();
            }
            if(!Schema::hasColumn('follow_up_events','follow_up_cancel_remarks')){
                $table->longText('follow_up_cancel_remarks')->nullable();
            }
            if(!Schema::hasColumn('follow_up_events','follow_up_complete_remarks')){
                $table->longText('follow_up_complete_remarks')->nullable();
            }
            if(!Schema::hasColumn('follow_up_events','follow_up_complete_date_time')){
                $table->dateTime('follow_up_complete_date_time')->nullable();
            }
            if(!Schema::hasColumn('follow_up_events','follow_up_cancel_date_time')){
                $table->dateTime('follow_up_cancel_date_time')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if(Schema::hasColumn('leads','lead_status_remarks')){
                $table->dropColumn('lead_status_remarks');
            }
            if(Schema::hasColumn('leads','lead_complete_date_time')){
                $table->dropColumn('lead_complete_date_time');
            }
            if(Schema::hasColumn('leads','lead_cancel_date_time')){
                $table->dropColumn('lead_cancel_date_time');
            }
        });
        Schema::table('follow_up_events', function (Blueprint $table) {
            if(Schema::hasColumn('follow_up_events','lead_status_remarks')){
                $table->dropColumn('lead_status_remarks');
            }
            if(Schema::hasColumn('follow_up_events','lead_complete_date_time')){
                $table->dropColumn('lead_complete_date_time');
            }
            if(Schema::hasColumn('follow_up_events','follow_up_cancel_date_time')){
                $table->dropColumn('follow_up_cancel_date_time');
            }
        });
    }
};
