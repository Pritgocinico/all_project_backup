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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('emp_id')->nullable();
            $table->longText('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('status')->default(1)->comment("1= active and  0 = in active");
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('holidays', function (Blueprint $table) {
            if(!Schema::hasColumn('holidays', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
            }
        });
        Schema::table('infosheets', function (Blueprint $table) {
            if(!Schema::hasColumn('infosheets', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
