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
        Schema::table('follow_up_comment_files', function (Blueprint $table) {
            if(!Schema::hasColumn('follow_up_comment_files','follow_up_id')){
                $table->unsignedBigInteger('follow_up_id')->nullable()->change();
                $table->foreign('follow_up_id')->references('id')->on('follow_up_events')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('follow_up_comment_files', function (Blueprint $table) {
            
        });
    }
};
