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
        Schema::create('user_recipients', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->integer('com_checkbox')->nullable();
            $table->text('email')->nullable();
            $table->string('phone')->nullable();
            $table->integer('sent')->default(0)->nullable();
            $table->timestamp('last_sent')->nullable();
            $table->integer('status')->default(0);
            $table->integer('email_activity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_recipients');
    }
};
