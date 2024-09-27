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
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'material_received_selection')) {
                $table->unsignedBigInteger('material_received_selection')->default(0);
            }
            if (!Schema::hasColumn('projects', 'material_received_by')) {
                $table->string('material_received_by')->nullable();
            }
            if (!Schema::hasColumn('projects', 'material_received_date')) {
                $table->timestamp('material_received_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('material_received_selection');
            $table->dropColumn('material_received_by');
            $table->dropColumn('material_received_date');
        });
    }
};
