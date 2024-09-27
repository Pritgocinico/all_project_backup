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
        Schema::create('salaryslips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emp_id')->nullable();
            $table->string('month')->nullable();
            $table->unsignedBigInteger('working_days')->nullable();
            $table->unsignedBigInteger('present_days')->nullable();
            $table->unsignedBigInteger('leaves')->nullable();
            $table->unsignedBigInteger('overtime')->nullable();
            $table->double('pt',8,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaryslips');
    }
};
