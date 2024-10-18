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
        if (!Schema::hasTable('follow_up_events')) {
            Schema::create('follow_up_events', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('type_id')->nullable();
                $table->unsignedBigInteger('lead_id')->nullable();
                $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
                $table->unsignedBigInteger('type')->nullable();
                $table->string('followup_type')->nullable();
                $table->string('event_name')->nullable();
                $table->date('event_start')->nullable();
                $table->date('event_end')->nullable();
                $table->string('remarks')->nullable();
                $table->string('file')->nullable();
                $table->string('status')->nullable();
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
        Schema::dropIfExists('followup_events');
    }
};
