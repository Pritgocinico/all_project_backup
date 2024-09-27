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
        Schema::table('fitting_done_tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('fitting_done_tasks', 'issue_id')) {
                $table->unsignedBigInteger('issue_id')->nullable();
            }
        });
        Schema::table('workshop_done_tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('workshop_done_tasks', 'issue_id')) {
                $table->unsignedBigInteger('issue_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fitting_done_tasks', function (Blueprint $table) {
            $table->dropColumn('issue_id');
        });
        Schema::table('workshop_done_tasks', function (Blueprint $table) {
            $table->dropColumn('issue_id');
        });
    }
};
