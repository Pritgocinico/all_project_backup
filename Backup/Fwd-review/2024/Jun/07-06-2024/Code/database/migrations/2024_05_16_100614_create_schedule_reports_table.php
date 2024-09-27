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
        if (!Schema::hasTable('schedule_reports')) {
            Schema::create('schedule_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('business_id')->nullable();
                $table->string('frequency')->nullable();
                $table->string('daily_interval')->nullable();
                $table->string('weekly_interval')->nullable();
                $table->string('monthly_date')->nullable();
                $table->string('schedule')->nullable();
                $table->string('timeframe_cover_date')->nullable();
                $table->string('timeframe_cover_type')->nullable();
                $table->longText('report_schedule')->nullable();
                $table->longText('add_recipient')->nullable();
                $table->date('schedule_date')->nullable();
                $table->enum('status',['1','0'])->default('1')->comment('1=active,0=inactive');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_reports');
    }
};
