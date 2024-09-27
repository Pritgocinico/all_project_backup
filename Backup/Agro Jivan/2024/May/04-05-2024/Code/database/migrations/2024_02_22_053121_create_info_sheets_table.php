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
        Schema::create('info_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('info_sheet')->nullable();
            $table->longText('description')->nullable();
            $table->enum('status',[1,0])->default(1)->comment('1=Active and 0=Inactive');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_sheets');
    }
};
