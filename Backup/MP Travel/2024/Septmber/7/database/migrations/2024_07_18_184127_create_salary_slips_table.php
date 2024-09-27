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
        Schema::create('salary_slips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emp_id')->nullable();
            $table->unsignedBigInteger('month')->nullable();
            $table->unsignedInteger('year')->nullable();
            $table->unsignedBigInteger('total_working_days')->nullable();
            $table->unsignedBigInteger('present_days')->nullable();
            $table->unsignedBigInteger('payable_salary')->nullable();
            $table->string('file_path')->nullable();
            $table->unsignedBigInteger('leave')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_slips');
    }
};
