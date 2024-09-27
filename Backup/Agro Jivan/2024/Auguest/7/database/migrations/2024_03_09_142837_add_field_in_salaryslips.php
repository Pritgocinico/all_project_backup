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
        Schema::table('salaryslips', function (Blueprint $table) {
            $table->enum('status',[1,0])->default(1)->comment('1=Active and 0=InActive');
            $table->double('payable_salary',8,2)->nullable();
            $table->softDeletes();
        });
        Schema::table('info_sheets', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salaryslips', function (Blueprint $table) {
            //
        });
    }
};
