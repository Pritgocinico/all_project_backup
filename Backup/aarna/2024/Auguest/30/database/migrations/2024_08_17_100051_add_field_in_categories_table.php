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
        Schema::table('categories', function (Blueprint $table) {
            if(!Schema::hasColumn('categories','insurance_type')){
                $table->unsignedBigInteger('insurance_type')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if(Schema::hasColumn('categories','insurance_type')){
                $table->dropColumn('insurance_type');
            }
        });
    }
};
