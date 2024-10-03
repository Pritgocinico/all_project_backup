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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('lead_source')->nullable();
            $table->string('customer_type')->nullable();
            $table->string('problem_duration')->nullable();
            $table->string('for_whom')->nullable();
            $table->string('remarks')->nullable();
            $table->string('send_whatsapp')->nullable();
            $table->date('lead_date')->nullable();
            $table->unsignedBigInteger('product')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
