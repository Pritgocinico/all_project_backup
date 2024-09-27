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
        Schema::create('quotation_uploads', function (Blueprint $table) {
            $table->id();
            $table->integer('quotation_id')->nullable();
            $table->integer('quotation_file_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->string('file')->nullable();
            $table->text('file_name')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_uploads');
    }
};
