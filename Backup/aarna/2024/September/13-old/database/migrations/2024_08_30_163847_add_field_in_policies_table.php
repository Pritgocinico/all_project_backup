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
        Schema::table('policies', function (Blueprint $table) {
            if(!Schema::hasColumn('policies','health_sub_category')){
                $table->unsignedBigInteger('health_sub_category')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policies', function (Blueprint $table) {
            if(Schema::hasColumn('policies','health_sub_category')){
                $table->dropColumn('health_sub_category');
            }
        });
    }
};
