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
        Schema::table('leaves', function (Blueprint $table) {
            if (!Schema::hasColumn('leaves', 'leave_feature')) {
                $table->unsignedBigInteger('leave_feature')->after('leave_status')->nullable()->comment('0=Half Day and 1=Full Day');
            }
            if (Schema::hasColumn('leaves', 'total_leave_day')) {
                $table->double('total_leave_day', '8,2')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            if (Schema::hasColumn('leaves', 'leave_feature')) {
                $table->dropIfExists('leave_feature');
            }
            if (Schema::hasColumn('leaves', 'total_leave_day')) {
                $table->unsignedBigInteger('total_leave_day')->nullable();
            }
        });
    }
};
