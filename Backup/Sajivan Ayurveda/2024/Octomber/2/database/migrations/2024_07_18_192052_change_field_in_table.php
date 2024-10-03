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
            if(Schema::hasColumn('salary_slips', 'month')) {
                $table->string('month')->nullable()->change();
            }
        });
        Schema::table('salary_slip_details', function (Blueprint $table) {
            if(Schema::hasColumn('salary_slip_details', 'month')) {
                $table->string('month')->nullable()->change();
            }
            if(!Schema::hasColumn('salary_slip_details', 'emp_id')) {
                $table->unsignedBigInteger('emp_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_slips', function (Blueprint $table) {
            $table->unsignedBigInteger('month')->nullable()->change();
        });
        Schema::table('salary_slip_details', function (Blueprint $table) {
            $table->unsignedBigInteger('month')->nullable()->change();
            $table->dropColumn('emp_id');
        });
    }
};
