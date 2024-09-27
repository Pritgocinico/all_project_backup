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
        Schema::dropIfExists('holiday');
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('holiday_name')->nullable();
            $table->date('holiday_date');
            $table->string('status')->default(1)->comment("1= active and  0 = in active");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holiday');
    }
};
