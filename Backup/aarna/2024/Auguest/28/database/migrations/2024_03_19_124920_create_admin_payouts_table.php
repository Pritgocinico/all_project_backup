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
        Schema::create('admin_payouts', function (Blueprint $table) {
            $table->id();
            $table->integer('company')->nullable();
            $table->integer('category')->nullable();
            $table->string('type')->nullable();
            $table->float('value')->nullable();
            $table->integer('created_by')->default(1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_payouts');
    }
};
