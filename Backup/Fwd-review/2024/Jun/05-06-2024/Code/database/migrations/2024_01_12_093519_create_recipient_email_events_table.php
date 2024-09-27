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
        Schema::create('recipient_email_events', function (Blueprint $table) {
            $table->id();
            $table->integer('recipient_id')->nullable();
            $table->string('event')->nullable();
            $table->text('sent_email')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('link_url')->nullable();
            $table->string('email_adress')->nullable();
            $table->string('bounce_sub_type')->nullable();
            $table->string('diagnostic_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipient_email_events');
    }
};
