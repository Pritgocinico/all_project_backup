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
        Schema::create('follow_up_comments', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('follow_id')->nullable();
            $table->foreign('follow_id')->references('id')->on('follow_up_events')->onDelete('cascade');
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->longText('comment')->nullable();
            $table->string('message_file')->nullable();
            $table->string('message_type')->comment('file and text')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_up_comments');
    }
};
