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
        Schema::table('service_preference_tags', function (Blueprint $table) {
            if(!Schema::hasColumn('service_preference_tags','status')){
                $table->tinyInteger('status')->default('1')->comment('1=active and 0= inactive');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_preference_tags', function (Blueprint $table) {
            
        });
    }
};
