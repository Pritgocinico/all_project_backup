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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->unique();
            $table->bigInteger('role');
            $table->text('image')->nullable();
            $table->text('address')->nullable();
            $table->string('floor_no')->nullable();
            $table->text('locality')->nullable();
            $table->text('city')->nullable();
            $table->text('state')->nullable();
            $table->text('country')->nullable();
            $table->text('gst_number')->nullable();
            $table->integer('agent')->default('0');
            $table->text('transport')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('password1')->nullable();
            $table->string('transport')->nullable();
            $table->text('headquarter')->nullable();
            $table->bigInteger('status')->default('0');
            $table->bigInteger('agent_status')->default('0');
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_login_ip_address')->nullable();
            $table->rememberToken();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
