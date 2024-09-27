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
        Schema::create('discount_type_detail', function (Blueprint $table) {
            $table->id();
            $table->string('discount_code')->nullable();
            $table->string('discount_type')->nullable();
            $table->double('discount_value',"8","2")->nullable();
            $table->string('applies_to')->nullable();
            $table->string('combination')->nullable();
            $table->date('active_start_date')->nullable();
            $table->time('active_start_time')->nullable();
            $table->date('active_end_date')->nullable();
            $table->time('active_end_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_type_detail');
    }
};
