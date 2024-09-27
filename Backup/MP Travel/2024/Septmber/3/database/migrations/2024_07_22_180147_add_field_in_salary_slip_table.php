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
        Schema::table('salary_slips', function (Blueprint $table) {
            if(!Schema::hasColumn('salary_slips', 'present_day')) {
                $table->unsignedBigInteger('present_day')->nullable();
            }
            if(!Schema::hasColumn('salary_slips', 'absent_day')) {
                $table->unsignedBigInteger('absent_day')->nullable();
            }
            if(!Schema::hasColumn('salary_slips', 'holiday')) {
                $table->unsignedBigInteger('holiday')->nullable();
            }
            if(!Schema::hasColumn('salary_slips', 'week_off')) {
                $table->unsignedBigInteger('week_off')->nullable();
            }
            if(!Schema::hasColumn('salary_slips', 'paid_day')) {
                $table->unsignedBigInteger('paid_day')->nullable();
            }
            if(!Schema::hasColumn('salary_slips', 'total_over_time')) {
                $table->unsignedBigInteger('total_over_time')->nullable();
            }
        });
        Schema::table('attendances', function (Blueprint $table) {
            if(Schema::hasColumn('attendances', 'over_time')) {
                $table->unsignedBigInteger('over_time')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_slips', function (Blueprint $table) {
            $table->dropColumn(['present_day', 'absent_day', 'holiday', 'week_off', 'paid_day', 'total_over_time']);
        });
        Schema::table('attendances', function (Blueprint $table) {
            if(Schema::hasColumn('attendances', 'over_time')) {
                $table->time('over_time')->nullable();
            }
        });
    }
};
