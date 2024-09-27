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
        Schema::create('fitting_done_tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id')->nullable();
            $table->integer('question_id')->nullable();
            $table->string('chk')->default('off');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fitting_done_tasks');
    }
};
