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
        Schema::create('module_modified_logs', function (Blueprint $table) {
            $table->id();
            $table->string('module_name')->nullable();
            $table->unsignedBigInteger('module_task_id')->nullable();
            $table->longText('description')->nullable();
            $table->string('column_name')->nullable();
            $table->string('new_value')->nullable();
            $table->string('original_value')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_modified_logs');
    }
};
