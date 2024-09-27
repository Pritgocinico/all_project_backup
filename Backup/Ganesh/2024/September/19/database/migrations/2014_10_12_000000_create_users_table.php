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
            $table->string('phone')->nullable();
            $table->bigInteger('role');
            $table->text('address')->nullable();
            $table->text('city')->nullable();
            $table->text('state')->default('Gujarat');
            $table->text('zipcode')->nullable();
            $table->text('image')->nullable();
            $table->string('password')->nullable();
            $table->bigInteger('status')->default('0');
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_login_ip_address')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
