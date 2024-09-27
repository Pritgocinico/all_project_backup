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
        Schema::create('user_email_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('email_name')->nullable();
            $table->integer('delay_days')->default(0);
            $table->text('subject')->nullable();
            $table->text('email_html')->nullable();
            $table->text('from_email')->nullable();
            $table->text('from_name')->nullable();
            $table->text('reply_to')->nullable();
            $table->integer('timezone')->nullable();
            $table->integer('custom_footer')->default(0);
            $table->text('custom_footer_html')->nullable();
            $table->integer('status')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_email_settings');
    }
};
