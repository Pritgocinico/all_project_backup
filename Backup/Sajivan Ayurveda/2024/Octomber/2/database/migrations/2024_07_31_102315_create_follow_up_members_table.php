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
        if (!Schema::hasTable('follow_up_members')) {

            Schema::create('follow_up_members', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('followup_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('followup_id')->references('id')->on('follow_up_events')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('followup_members');
    }
};
