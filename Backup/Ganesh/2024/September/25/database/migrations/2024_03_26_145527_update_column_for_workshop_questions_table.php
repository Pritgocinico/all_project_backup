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
        Schema::table('workshop_questions', function (Blueprint $table) {
            $table->integer('project_id')->nullable()->change();
            $table->string('workshop_question')->nullable()->change();
            $table->integer('created_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workshop_questions', function (Blueprint $table) {
            $table->integer('project_id')->nullable(false)->change();
            $table->string('workshop_question')->nullable(false)->change();
            $table->integer('created_by')->nullable(false)->change();
        });
    }
};
