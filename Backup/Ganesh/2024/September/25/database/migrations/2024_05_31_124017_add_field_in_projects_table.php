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
            if (!Schema::hasColumn('projects', 'material_received_number')) {
                $table->unsignedBigInteger('material_received_number')->nullable();
            }
        });
        Schema::table('additional_project_details', function (Blueprint $table) {
            if (!Schema::hasColumn('additional_project_details', 'material_received_number')) {
                $table->unsignedBigInteger('material_received_number')->nullable();
            }
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('material_received_number');
        });
        Schema::table('additional_project_details', function (Blueprint $table) {
            $table->dropColumn('material_received_number');
        });
    }
};
