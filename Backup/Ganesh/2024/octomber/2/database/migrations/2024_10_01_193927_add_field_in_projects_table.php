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
        Schema::table('measurementfiles', function (Blueprint $table) {
            if(!Schema::hasColumn('measurementfiles','file_type')) {
                $table->unsignedBigInteger('file_type')->default(0)->comment('0=Rough and 1=Final')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('measurementfiles', function (Blueprint $table) {
            //
        });
    }
};
