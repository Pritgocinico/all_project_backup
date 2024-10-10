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
        Schema::create('lead_check_list_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_check_list_id')->nullable();
            $table->foreign('lead_check_list_id')->references('id')->on('lead_check_lists')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_check_list_items');
    }
};
