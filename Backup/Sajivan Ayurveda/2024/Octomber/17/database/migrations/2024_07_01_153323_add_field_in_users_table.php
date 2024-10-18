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
        Schema::table('users', function (Blueprint $table) {
            if(!Schema::hasColumn('users', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable();
                $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            }
            if(!Schema::hasColumn('users', 'designation_id')) {
                $table->unsignedBigInteger('designation_id')->nullable();
                $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('department_id');
            $table->dropColumn('designation_id');
        });
    }
};
