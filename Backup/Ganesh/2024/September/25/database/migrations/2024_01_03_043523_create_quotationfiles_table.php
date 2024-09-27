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
        Schema::dropIfExists('quotationfiles');
        Schema::create('quotationfiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('quotation_id')->nullable();
            $table->string('quotation')->nullable();
            $table->string('type')->nullable();
            $table->string('image')->nullable();
            $table->string('description')->nullable();
            $table->float('cost')->nullable();
            $table->float('project_cost')->nullable();
            $table->integer('reject_reason')->default('0')->nullable();
            $table->text('reject_note')->nullable();
            $table->integer('final')->default('0')->nullable();
            $table->integer('status')->default('0')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('quotationfiles', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
        Schema::table('quotationfiles', function (Blueprint $table) {
            $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotationfiles');
    }
};
