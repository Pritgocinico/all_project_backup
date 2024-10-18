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
        
        Schema::table('follow_up_events', function (Blueprint $table) {
            if(!Schema::hasColumn('follow_up_events','created_by')){
                $table->unsignedBigInteger('created_by')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('follow_up_events', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });
    }
};
